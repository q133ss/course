<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $roleSlug
     */
    public function handle(Request $request, Closure $next, string $roleSlug): Response|RedirectResponse
    {
        $user = Auth::user();

        if (! $user || $user->role?->slug !== $roleSlug) {
            abort(403);
        }

        return $next($request);
    }
}
