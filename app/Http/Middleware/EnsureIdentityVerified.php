<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureIdentityVerified
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if (blank($user)) {
            return redirect()->route('login');
        }

        if ($user->isAdminOrStaff()) {
            return $next($request);
        }

        if ($user->verification_status === User::VERIFICATION_STATUS_APPROVED) {
            return $next($request);
        }

        if ($user->verification_status === User::VERIFICATION_STATUS_PENDING) {
            return redirect()->route('verification.pending');
        }

        return redirect()->route('profile.update');
    }
}

