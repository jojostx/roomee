<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Spatie\Onboard\Facades\Onboard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Onboard::addStep('Add Contact Channels')
            ->link('/settings/contact-channels')
            ->cta('Add Contact Channels')
            ->attributes([
                'body' => 'By default, <span class="font-semibold underline" x-tooltip.raw="Users who accept your Roommate Requests or whose Roommate Requests you accept">Potential roommates</span> can contact you via email. To be contacted via other means like Whatsapp, Twitter, Instagram or Facebook, set up your accounts as contact channels.',
            ])
            ->completeIf(function (User $model) {
                return $model->contactChannels->count() > 0;
            });

        Onboard::addStep('Add Notification Channel')
            ->link('/settings/notifications')
            ->cta('Add Phone Number')
            ->attributes([
                'body' => 'By default, <span class="font-semibold underline" x-tooltip.raw="for example, when a user accepts your roommate request or sends you one">Notifications</span> will be sent to your email. To receive updates via SMS, add your phone number as a Notification channel.',
            ])
            ->completeIf(function (User $model) {
                return false;
            });

        // Onboard::addStep('Add Personality Profile')
        //     ->link('/settings/personality-profile')
        //     ->cta('Add Personality Profile')
        //     ->attributes([
        //         'body' => 'You can get better roommate recommendations by completing a personality test on <a href="#" target="_blank" class="font-semibold underline">Personacle</a> and sharing your Personacle Personality result code with us.',
        //     ])
        //     ->completeIf(function (User $model) {
        //         return false;
        //     });
    }
}
