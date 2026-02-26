<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Service\HelperResponse;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Ensure the authenticated user has one of the required roles.
     *
     * Usage:
     *   Route::middleware(['auth:api_token', 'role:admin'])
     *   Route::middleware(['auth:api_token', 'role:admin,consumer'])
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = auth()->user();

        if (! $user || ! in_array($user->type, $roles)) {
            return HelperResponse::error('Forbidden. You do not have permission to access this resource.', 403);
        }

        return $next($request);
    }
}
