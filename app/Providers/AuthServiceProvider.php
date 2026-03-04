<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Listing;
use App\Policies\ListingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Listing::class => ListingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Password::defaults(function () {
            $rule = Password::min(10);

            return $this->app->isProduction()
                ? $rule->min(10)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : $rule;
        });
    }
}
