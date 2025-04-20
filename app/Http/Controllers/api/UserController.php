<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Authorization check
        if ($request->user()->id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Combined validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'sometimes|string|nullable',
            'avatar' => 'sometimes|image|max:2048',
            'cover_image' => 'sometimes|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update text fields
        $user->fill($request->only(['name', 'username', 'bio']));

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarUrl = $this->cloudinary->uploadImage(
                $request->file('avatar'),
                'avatars'
            );
            $user->avatar = $avatarUrl;
        }

        // Handle cover upload
        if ($request->hasFile('cover_image')) {
            $coverUrl = $this->cloudinary->uploadImage(
                $request->file('cover_image'),
                'covers'
            );
            $user->cover_image = $coverUrl;
        }

        $user->save();

        return response()->json([
            'user' => $user
        ]);
    }

    public function updateAvatar(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Check if the authenticated user is the owner
        if ($request->user()->id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload new avatar to Cloudinary
        if ($request->hasFile('avatar')) {
            $avatarUrl = $this->cloudinary->uploadImage(
                $request->file('avatar'),
                'avatars'
            );

            $user->avatar = $avatarUrl;
            $user->save();

            return response()->json([
                'avatarUrl' => $avatarUrl
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'No avatar file provided'
        ], 400);
    }

    public function updateCover(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Check if the authenticated user is the owner
        if ($request->user()->id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'cover' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload new cover to Cloudinary
        if ($request->hasFile('cover')) {
            $coverUrl = $this->cloudinary->uploadImage(
                $request->file('cover'),
                'covers'
            );

            $user->cover_image = $coverUrl;
            $user->save();

            return response()->json([
                'coverUrl' => $coverUrl
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'No cover image file provided'
        ], 400);
    }

    public function recipes($id)
    {
        $user = User::findOrFail($id);
        $recipes = $user->recipes()->latest()->paginate(12);

        return response()->json([
            'recipes' => $recipes
        ]);
    }

    public function collections($id)
    {
        $user = User::findOrFail($id);
        $collections = $user->collections()->latest()->paginate(12);

        return response()->json([
            'collections' => $collections
        ]);
    }

    public function saved($id, Request $request)
    {
        $user = User::findOrFail($id);

        // Check if the authenticated user is the owner
        if ($request->user()->id !== $user->id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $recipes = $user->savedRecipes()->latest()->paginate(12);

        return response()->json([
            'recipes' => $recipes
        ]);
    }
}
