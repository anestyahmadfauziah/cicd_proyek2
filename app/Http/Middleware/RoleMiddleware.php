<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\Guard;

final class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ?string $guard = null,
        string ...$roles
    ): Response {
        $auth = $guard ? Auth::guard($guard) : Auth::guard();

        if (!$auth->check()) {
            return redirect()->route('login');
        }

        $user = $auth->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}