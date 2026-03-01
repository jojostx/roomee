<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountNotSuspended
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (filled($user) && $user->isSuspended()) {
            Auth::guard('web')->logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ]);
        }

        return $next($request);
    }
}

