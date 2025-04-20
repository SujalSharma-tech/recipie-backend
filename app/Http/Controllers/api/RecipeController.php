<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    public function index(Request $request)
    {
        $query = Recipe::query()->with('author');

        // Apply filters
        if ($request->has('cuisine') && $request->cuisine !== 'all_cuisines') {
            $query->where('cuisine', $request->cuisine);
        }

        if ($request->has('difficulty') && $request->difficulty !== 'any_difficulty') {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->has('minTime')) {
            $query->where('time', '>=', $request->minTime);
        }

        if ($request->has('maxTime')) {
            $query->where('time', '<=', $request->maxTime);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Handle ingredients filter
        if ($request->has('ingredients') && is_array($request->ingredients)) {
            foreach ($request->ingredients as $ingredient) {
                $query->whereJsonContains('ingredients', ['name' => $ingredient]);
            }
        }

        // Default to public recipes unless user is authenticated and requesting their own
        if ($request->user() && $request->has('author_id') && $request->author_id == $request->user()->id) {
            $query->where('author_id', $request->user()->id);
        } else {
            $query->where('is_public', true);
        }

        $recipes = $query->latest()->paginate($request->per_page ?? 12);

        return response()->json([
            'recipes' => $recipes->items(),
            'total' => $recipes->total(),
            'page' => $recipes->currentPage(),
            'limit' => $recipes->perPage()
        ]);
    }

    public function store(Request $request)
    {
        // Pre-process form data for validation
        $data = $request->all();
        Log::info('Recipe data:', ['data' => $data]);

        // Convert string boolean values to actual booleans
        if (isset($data['is_public']) && is_string($data['is_public'])) {
            $data['is_public'] = filter_var($data['is_public'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($data['allow_copy']) && is_string($data['allow_copy'])) {
            $data['allow_copy'] = filter_var($data['allow_copy'], FILTER_VALIDATE_BOOLEAN);
        }

        // Handle ingredients format - keep as string array but ensure it's an array
        if (isset($data['ingredients']) && is_string($data['ingredients'])) {
            try {
                $data['ingredients'] = json_decode($data['ingredients'], true);
            } catch (\Exception $e) {
                $data['ingredients'] = [];
            }
        }

        // Handle steps format - keep as string array but ensure it's an array
        if (isset($data['steps']) && is_string($data['steps'])) {
            try {
                $data['steps'] = json_decode($data['steps'], true);
            } catch (\Exception $e) {
                $data['steps'] = [];
            }
        }

        // Handle other JSON fields
        $jsonFields = ['tags'];
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                try {
                    $data[$field] = json_decode($data[$field], true);
                } catch (\Exception $e) {
                    $data[$field] = [];
                }
            }
        }

        // Special handling for nutrition field
        if (isset($data['nutrition']) && is_string($data['nutrition'])) {
            try {

                // First decode the JSON string
                $nutritionData = json_decode($data['nutrition'], true);


                // If it's already an array/object, use it directly
                if (is_array($nutritionData)) {
                    $data['nutrition'] = $nutritionData;
                }
                // If it's still a string (double-encoded JSON), decode again
                else if (is_string($nutritionData)) {
                    $data['nutrition'] = json_decode($nutritionData, true);
                }
                // Fallback
                else {
                    $data['nutrition'] = [];
                }
            } catch (\Exception $e) {
                $data['nutrition'] = [];
            }
        }

        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:5120',
            'youtube_video' => 'required|string|max:255',
            'cooking_time' => 'required|string',
            'difficulty' => 'required|string|in:Easy,Medium,Hard',
            'servings' => 'required|string',
            'cuisine_type' => 'required|string|max:100',
            'is_public' => 'sometimes|boolean',
            'allow_copy' => 'sometimes|boolean',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'string', // Validate each ingredient is a string
            'steps' => 'required|array|min:1',
            'steps.*' => 'string', // Validate each step is a string
            'nutrition' => 'sometimes|array',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload
        // $imagePath = $request->file('image')->store('recipes', 'public');
        if ($request->hasFile('image')) {
            $imagePath = $this->cloudinary->uploadImage(
                $request->file('image'),
                'recipes'
            );
        } else {
            $imagePath = null;
        }

        // Make sure to include all required fields

        $recipe = new Recipe([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $imagePath,
            'cooking_time' => $data['cooking_time'],
            'youtube_video' => $data['youtube_video'],
            'difficulty' => $data['difficulty'],
            'servings' => $data['servings'],
            'cuisine_type' => $data['cuisine_type'],
            'is_public' => $data['is_public'] ?? true,
            'allow_copy' => $data['allow_copy'] ?? false,
            'author_id' => $request->user()->id,
            'ingredients' => $data['ingredients'], // Keep as a simple array of strings
            'steps' => $data['steps'],
            'nutrition' => $data['nutrition'] ?? [],
            'tags' => $data['tags'] ?? [],
            'views' => 0,
        ]);

        $recipe->save();

        return response()->json([
            'recipe' => $recipe->load('author')
        ], 201);
    }

    public function show($id)
    {
        $recipe = Recipe::with(['author', 'comments.user'])->findOrFail($id);

        // Default status values
        $data = [
            'recipe' => $recipe,

        ];


        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        // Check if the authenticated user is the owner
        if ($request->user()->id !== $recipe->author_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Pre-process form data for validation
        $data = $request->all();
        Log::info('Recipe update data:', ['data' => $data]);

        // Convert string boolean values to actual booleans
        if (isset($data['is_public']) && is_string($data['is_public'])) {
            $data['is_public'] = filter_var($data['is_public'], FILTER_VALIDATE_BOOLEAN);
        }

        if (isset($data['allow_copy']) && is_string($data['allow_copy'])) {
            $data['allow_copy'] = filter_var($data['allow_copy'], FILTER_VALIDATE_BOOLEAN);
        }

        // Parse JSON string fields into arrays
        $jsonFields = ['ingredients', 'steps', 'tags'];
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                try {
                    $data[$field] = json_decode($data[$field], true);
                } catch (\Exception $e) {
                    $data[$field] = [];
                }
            }
        }

        // Special handling for nutrition field
        if (isset($data['nutrition']) && is_string($data['nutrition'])) {
            try {
                // First decode the JSON string
                $nutritionData = json_decode($data['nutrition'], true);

                // If it's already an array/object, use it directly
                if (is_array($nutritionData)) {
                    $data['nutrition'] = $nutritionData;
                }
                // If it's still a string (double-encoded JSON), decode again
                else if (is_string($nutritionData)) {
                    $data['nutrition'] = json_decode($nutritionData, true);
                }
                // Fallback
                else {
                    $data['nutrition'] = [];
                }
            } catch (\Exception $e) {
                $data['nutrition'] = [];
            }
        }

        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|image|max:5120',
            'youtube_video' => 'sometimes|string|max:255',
            'cooking_time' => 'sometimes|string',
            'difficulty' => 'sometimes|string|in:Easy,Medium,Hard',
            'servings' => 'sometimes|string',
            'cuisine_type' => 'sometimes|string|max:100',
            'is_public' => 'sometimes|boolean',
            'allow_copy' => 'sometimes|boolean',
            'ingredients' => 'sometimes|array|min:1',
            'ingredients.*' => 'string', // Validate each ingredient is a string
            'steps' => 'sometimes|array|min:1',
            'steps.*' => 'string', // Validate each step is a string
            'nutrition' => 'sometimes|array',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // No need to delete old images from Cloudinary
            $imagePath = $this->cloudinary->uploadImage(
                $request->file('image'),
                'recipes'
            );
            $recipe->image = $imagePath;
        }

        // Update fields only if they are provided in the request
        $updateFields = [
            'title',
            'description',
            'youtube_video',
            'cooking_time',
            'difficulty',
            'servings',
            'cuisine_type',
            'is_public',
            'allow_copy',
            'ingredients',
            'steps',
            'nutrition',
            'tags'
        ];

        foreach ($updateFields as $field) {
            if (isset($data[$field])) {
                $recipe->$field = $data[$field];
            }
        }

        $recipe->save();

        return response()->json([
            'recipe' => $recipe->load('author')
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        // Check if the authenticated user is the owner
        if ($request->user()->id !== $recipe->author_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Delete image
        if ($recipe->image && Storage::exists($recipe->image)) {
            Storage::delete($recipe->image);
        }

        // Delete step videos
        foreach ($recipe->steps as $step) {
            if (isset($step['video']) && Storage::exists($step['video'])) {
                Storage::delete($step['video']);
            }
        }

        $recipe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recipe deleted successfully'
        ]);
    }

    public function like(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        // Check if already liked
        if (!$recipe->likedBy()->where('user_id', $user->id)->exists()) {
            $recipe->likedBy()->attach($user->id);
        }

        return response()->json([
            'likes' => $recipe->likedBy()->count()
        ]);
    }

    public function unlike(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        $recipe->likedBy()->detach($user->id);

        return response()->json([
            'likes' => $recipe->likedBy()->count()
        ]);
    }

    public function save(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        // Check if already saved
        if (!$user->savedRecipes()->where('recipe_id', $recipe->id)->exists()) {
            $user->savedRecipes()->attach($recipe->id);
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function unsave(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);
        $user = $request->user();

        $user->savedRecipes()->detach($recipe->id);

        return response()->json([
            'success' => true
        ]);
    }

    public function copy(Request $request, $id)
    {
        $originalRecipe = Recipe::findOrFail($id);

        // Check if copying is allowed
        if (!$originalRecipe->allow_copy && $request->user()->id !== $originalRecipe->author_id) {
            return response()->json([
                'error' => true,
                'message' => 'Copying this recipe is not allowed by the author'
            ], 403);
        }

        // Create a copy of the recipe
        $newRecipe = $originalRecipe->replicate();
        $newRecipe->author_id = $request->user()->id;
        $newRecipe->views = 0;
        $newRecipe->title = "Copy of " . $originalRecipe->title;

        // Copy the image
        if ($originalRecipe->image && Storage::exists('public/' . $originalRecipe->image)) {
            $newImagePath = 'recipes/' . uniqid() . '_' . basename($originalRecipe->image);
            Storage::copy('public/' . $originalRecipe->image, 'public/' . $newImagePath);
            $newRecipe->image = $newImagePath;
        }

        $newRecipe->save();

        return response()->json([
            'recipe' => $newRecipe->load('author'),
            'message' => 'Recipe copied successfully'
        ], 201);
    }

    public function getStatus(Request $request, $id)
    {
        if (!$request->user()) {
            return response()->json([
                'is_liked' => false,
                'is_saved' => false
            ]);
        }

        $user = $request->user();

        return response()->json([
            'is_liked' => $user->likedRecipes()->where('recipe_id', $id)->exists(),
            'is_saved' => $user->savedRecipes()->where('recipe_id', $id)->exists()
        ]);
    }
}
