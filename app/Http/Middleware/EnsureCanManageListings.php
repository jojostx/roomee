<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureCanManageListings
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = $request->user();

        if (blank($user)) {
            return redirect()->route('login');
        }

        if ($user->canManageListings()) {
            return $next($request);
        }

        if (!$user->isRegularUser()) {
            abort(403);
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if (!$user->profile_updated) {
            return redirect()->route('profile.update');
        }

        if ($user->isPendingVerification()) {
            return redirect()->route('verification.pending');
        }

        return redirect()->route('profile.update');
    }
}

