<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $comments = $recipe->comments()->with('user')->latest()->paginate(20);

        return response()->json([
            'comments' => $comments
        ]);
    }

    public function store(Request $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = new Comment([
            'recipe_id' => $recipe->id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        $comment->save();

        return response()->json([
            'comment' => $comment->load('user')
        ], 201);
    }

    public function update(Request $request, $recipeId, $commentId)
    {
        $comment = Comment::where('recipe_id', $recipeId)
            ->where('id', $commentId)
            ->firstOrFail();

        // Check if the authenticated user is the owner
        if ($request->user()->id !== $comment->user_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'comment' => $comment->load('user')
        ]);
    }

    public function destroy(Request $request, $recipeId, $commentId)
    {
        $comment = Comment::where('recipe_id', $recipeId)
            ->where('id', $commentId)
            ->firstOrFail();

        // Check if the authenticated user is the owner or recipe author
        $recipe = Recipe::findOrFail($recipeId);
        if ($request->user()->id !== $comment->user_id && $request->user()->id !== $recipe->author_id) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
