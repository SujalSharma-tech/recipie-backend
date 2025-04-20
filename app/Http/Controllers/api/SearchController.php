<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function recipes(Request $request)
    {
        $query = $request->get('q', '');
        $perPage = $request->get('per_page', 12);

        $recipes = Recipe::where('is_public', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('cuisine', 'like', "%{$query}%")
                    ->orWhere('tags', 'like', "%{$query}%");
            })
            ->with('author')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'recipes' => $recipes
        ]);
    }

    public function users(Request $request)
    {
        $query = $request->get('q', '');
        $perPage = $request->get('per_page', 12);

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->withCount(['recipes', 'collections'])
            ->paginate($perPage);

        return response()->json([
            'users' => $users
        ]);
    }

    public function collections(Request $request)
    {
        $query = $request->get('q', '');
        $perPage = $request->get('per_page', 12);

        $collections = Collection::where('is_public', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('user')
            ->withCount('recipes')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'collections' => $collections
        ]);
    }
}
