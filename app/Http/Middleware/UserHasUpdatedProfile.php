<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class UserHasUpdatedProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $profileIsUpdated = $request->user()->profile_updated;

        if (!$profileIsUpdated) {
            return redirect(RouteServiceProvider::PROFILE);
        }

        return $next($request);
    }
}
