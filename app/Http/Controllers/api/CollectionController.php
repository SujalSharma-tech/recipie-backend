<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Recipe;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function index(Request $request)
    {
        $collections = $request->user()->collections()->withCount('recipes')->latest()->paginate(12);

        return response()->json([
            'collections' => $collections
        ]);
    }

    public function store(Request $request)
    {
        // Process form data for validation
        $data = $request->all();

        // Convert string boolean values to actual booleans
        if (isset($data['is_public']) && is_string($data['is_public'])) {
            $data['is_public'] = filter_var($data['is_public'], FILTER_VALIDATE_BOOLEAN);
        }

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
            'cover_image' => 'sometimes|image|max:5120',
            'is_public' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $this->cloudinary->uploadImage(
                $request->file('cover_image'),
                'collections'
            );
        }

        $collection = new Collection([
            'name' => $request->name,
            'description' => $request->description ?? '',
            'cover_image' => $coverPath,
            'user_id' => $request->user()->id,
            'is_public' => $data['is_public'] ?? true, // Use the converted value
        ]);

        $collection->save();

        return response()->json([
            'collection' => $collection
        ], 201);
    }

    public function show($id)
    {
        $collection = Collection::with('user')->withCount('recipes')->findOrFail($id);
        $recipes = $collection->recipes()->with('author')->paginate(12);

        return response()->json([
            'collection' => $collection,
            'recipes' => $recipes
        ]);
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if ($request->user()->id !== $collection->user_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Process form data for validation
        $data = $request->all();

        // Convert string boolean values to actual booleans
        if (isset($data['is_public']) && is_string($data['is_public'])) {
            $data['is_public'] = filter_var($data['is_public'], FILTER_VALIDATE_BOOLEAN);
        }

        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'cover_image' => 'sometimes|image|max:5120',
            'is_public' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('cover_image')) {
            $coverPath = $this->cloudinary->uploadImage(
                $request->file('cover_image'),
                'collections'
            );
            $collection->cover_image = $coverPath;
        }

        if ($request->has('name')) {
            $collection->name = $request->name;
        }

        if ($request->has('description')) {
            $collection->description = $request->description;
        }

        // Use the converted boolean value
        if (isset($data['is_public'])) {
            $collection->is_public = $data['is_public'];
        }

        $collection->save();

        return response()->json([
            'collection' => $collection
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if ($request->user()->id !== $collection->user_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($collection->cover_image && Storage::exists($collection->cover_image)) {
            Storage::delete($collection->cover_image);
        }

        $collection->recipes()->detach();

        $collection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collection deleted successfully'
        ]);
    }

    public function addRecipe(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if ($request->user()->id !== $collection->user_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $recipe = Recipe::findOrFail($request->recipe_id);

        if (!$collection->recipes()->where('recipe_id', $recipe->id)->exists()) {
            $collection->recipes()->attach($recipe->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Recipe added to collection'
        ]);
    }

    public function removeRecipe(Request $request, $id, $recipeId)
    {
        $collection = Collection::findOrFail($id);

        if ($request->user()->id !== $collection->user_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $collection->recipes()->detach($recipeId);

        return response()->json([
            'success' => true,
            'message' => 'Recipe removed from collection'
        ]);
    }

    public function recipes(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        // Get recipes with pagination
        $recipes = $collection->recipes()
            ->with('author')
            ->latest()
            ->paginate($request->per_page ?? 12);

        return response()->json([
            'collection' => [
                'id' => $collection->id,
                'name' => $collection->name,
                'description' => $collection->description,
            ],
            'recipes' => $recipes->items(),
            'total' => $recipes->total(),
            'page' => $recipes->currentPage(),
            'limit' => $recipes->perPage()
        ]);
    }
}
