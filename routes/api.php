<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CollectionController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\RatingController;
use App\Http\Controllers\api\RecipeController;
use App\Http\Controllers\api\SearchController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes

Route::get('/check', function () {
    return response()->json(['status' => 'API route working!']);
});
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Search routes
Route::get('/search/recipes', [SearchController::class, 'recipes']);
Route::get('/search/users', [SearchController::class, 'users']);
Route::get('/search/collections', [SearchController::class, 'collections']);
Route::get('/search/cuisines
', function () {
    return response()->json([]);
});

// Recipe routes (public)
Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{id}', [RecipeController::class, 'show']);
Route::get('/recipes/{recipeId}/comments', [CommentController::class, 'index']);
Route::get('/recipes/{recipeId}/ratings', [RatingController::class, 'index']);

// User routes (public)
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/recipes', [UserController::class, 'recipes']);
Route::get('/users/{id}/collections', [UserController::class, 'collections']);

// Collection routes (public)
Route::get('/collections/{id}', [CollectionController::class, 'show']);
Route::get('/collections/{id}/recipes', [CollectionController::class, 'recipes']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // User routes
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::put('/users/{id}/avatar', [UserController::class, 'updateAvatar']);
    Route::put('/users/{id}/cover', [UserController::class, 'updateCover']);
    Route::get('/users/{id}/saved', [UserController::class, 'saved']);

    // Recipe routes
    Route::post('/recipes', [RecipeController::class, 'store']);
    Route::put('/recipes/{id}', [RecipeController::class, 'update']);
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);
    Route::post('/recipes/{id}/like', [RecipeController::class, 'like']);
    Route::post('/recipes/{id}/unlike', [RecipeController::class, 'unlike']);
    Route::post('/recipes/{id}/save', [RecipeController::class, 'save']);
    Route::post('/recipes/{id}/unsave', [RecipeController::class, 'unsave']);
    Route::get('/recipes/{id}/getStatus', [RecipeController::class, 'getStatus']);
    Route::post('/recipes/{id}/copy', [RecipeController::class, 'copy']);

    // Comment routes
    Route::post('/recipes/{recipeId}/comments', [CommentController::class, 'store']);
    Route::put('/recipes/{recipeId}/comments/{commentId}', [CommentController::class, 'update']);
    Route::delete('/recipes/{recipeId}/comments/{commentId}', [CommentController::class, 'destroy']);

    // Rating routes
    Route::post('/recipes/{recipeId}/ratings', [RatingController::class, 'store']);

    // Collection routes
    Route::get('/collections', [CollectionController::class, 'index']);
    Route::post('/collections', [CollectionController::class, 'store']);
    Route::put('/collections/{id}', [CollectionController::class, 'update']);
    Route::delete('/collections/{id}', [CollectionController::class, 'destroy']);
    Route::post('/collections/{id}/recipes', [CollectionController::class, 'addRecipe']);
    Route::delete('/collections/{id}/recipes/{recipeId}', [CollectionController::class, 'removeRecipe']);
});
