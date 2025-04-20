<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function index($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $ratings = $recipe->ratings()->with('user')->get();
        $average = $ratings->avg('value');

        return response()->json([
            'ratings' => $ratings,
            'average' => $average,
            'count' => $ratings->count()
        ]);
    }

    public function store(Request $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        $validator = Validator::make($request->all(), [
            'value' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user already rated this recipe
        $existingRating = Rating::where('recipe_id', $recipe->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingRating) {
            $existingRating->value = $request->value;
            $existingRating->save();
            $rating = $existingRating;
        } else {
            $rating = new Rating([
                'recipe_id' => $recipe->id,
                'user_id' => $request->user()->id,
                'value' => $request->value,
            ]);
            $rating->save();
        }

        // Calculate new average
        $average = $recipe->ratings()->avg('value');

        return response()->json([
            'rating' => $rating,
            'average' => $average,
            'count' => $recipe->ratings()->count()
        ]);
    }
}
