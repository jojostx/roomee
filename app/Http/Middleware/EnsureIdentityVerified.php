<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureIdentityVerified
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = $request->user();

        if (blank($user)) {
            return redirect()->route('login');
        }

        if ($user->isAdminOrStaff()) {
            return $next($request);
        }

        if ($user->isPendingVerification()) {
            if ($request->routeIs('verification.pending')) {
                return $next($request);
            }

            return redirect()->route('verification.pending');
        }

        if (! $user->profile_updated || ! $user->isVerified()) {
            if ($request->routeIs('profile.update')) {
                return $next($request);
            }

            return redirect()->route('profile.update');
        }

        return $next($request);
    }
}
