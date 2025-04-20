<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar',
        'cover_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'joined_date' => 'datetime',
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'author_id');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function savedRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'saved_recipes', 'user_id', 'recipe_id')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get recipes that the user has liked
     */
    public function likedRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_likes', 'user_id', 'recipe_id')
            ->withTimestamps();
    }
}
