<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For API requests, don't redirect, just return null
        // This will cause the middleware to throw an unauthenticated exception
        // which will be converted to a JSON response
        return $request->expectsJson() ? null : '/login';
    }
}
