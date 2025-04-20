<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'cooking_time',
        'youtube_video',
        'difficulty',
        'servings',
        'cuisine_type',
        'is_public',
        'allow_copy',
        'author_id',
        'ingredients',
        'steps',
        'nutrition',
        'tags',
        'views',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'steps' => 'array',
        'nutrition' => 'array',
        'tags' => 'array',
        'is_public' => 'boolean',
        'allow_copy' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'recipe_likes', 'recipe_id', 'user_id')
            ->withTimestamps();
    }

    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'saved_recipes', 'recipe_id', 'user_id')
            ->withTimestamps();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_recipes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
