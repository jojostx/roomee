<?php

namespace App\Providers;

use App\Events\RoommateRequestUpdated;
use App\Listeners\SendPasswordHasBeenResetNotification;
use App\Listeners\SendRoommateRequestUpdatedNotifications;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        RoommateRequestUpdated::class => [
            SendRoommateRequestUpdatedNotifications::class,
        ],
        
        PasswordReset::class => [
            SendPasswordHasBeenResetNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
