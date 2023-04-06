<?php

namespace App\Http\Livewire\Pages\Settings;

use App\Enums\ContactChannelType;
use App\Models\User;
use Closure;
use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

class ContactChannelsSettingsPage extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    /**
     * facebook
     * instagram
     * twitter
     *  apply to connect social account
     *  generate a token {1122ax}
     *  send the token to our social account's inbox,
     *  come back and click verify
     *  this puts the account on pending_admin_approval
     */

    public $whatsapp;
    public $facebook;
    public $instagram;
    public $twitter;

    public bool $show_whatsapp_code = false;
    public bool $show_facebook_code = false;
    public bool $show_instagram_code = false;
    public bool $show_twitter_code = false;

    public function mount()
    {
        // $this->settings = new Settings($this->getAuthUser()->social_links);

        $this->facebookForm->fill([
            'facebook' => '',
        ]);

        $this->instagramForm->fill([
            'instagram' => null,
        ]);

        $this->twitterForm->fill([
            'twitter' => '',
        ]);
    }

    protected function getAuthUser(): User
    {
        return \auth()->user();
    }

    protected function getWhatsappFormSchema(): array
    {
        return [
            Section::make('Whatsapp')
                ->schema([
                    Forms\Components\TextInput::make('whatsapp')
                        ->label('Phone number')
                        ->placeholder('ex: 08034081480')
                        ->required()
                        ->reactive()
                        ->suffixAction(
                            fn (?string $state, Closure $set): Action =>
                            Action::make('generate-wa')
                                ->icon('heroicon-o-plus')
                                ->action(function () use ($state, $set) {
                                    if (blank($state)) {
                                        $this->addError('whatsapp', 'Your Whatsapp phone number cannot be empty.');
                                        return;
                                    }
                                    $this->validateOnly('whatsapp');
                                    $set('whatsapp-code', 'TEST');
                                    $set('show_whatsapp_code', true);
                                }),
                        ),

                    Forms\Components\TextInput::make('whatsapp-code')
                        ->label('Verification code')
                        ->disabled()
                        ->visible(fn (Closure $get) => $get('whatsapp') && $this->show_whatsapp_code),

                    Forms\Components\Placeholder::make('whatsapp-account')
                        ->label('Our whatsapp account')
                        ->content('https://whatsapp.com/cotenanty'),

                    Forms\Components\Placeholder::make('submit-wa')
                        ->disableLabel()
                        ->content(self::getSubmitButton())
                        ->visible(fn (Closure $get) => $get('whatsapp') && $this->show_whatsapp_code)
                ])
                ->compact()
                ->collapsible()
        ];
    }

    protected function getFacebookFormSchema(): array
    {
        return [
            Section::make('Facebook')
                ->schema([
                    Forms\Components\TextInput::make('facebook')
                        ->label('Profile link')
                        ->placeholder('ex: https://facebook.com/John.doe')
                        ->required()
                        ->activeUrl()
                        ->startsWith([
                            'https://www.facebook.com/',
                            'https://facebook.com/',
                            'https://web.facebook.com/',
                            'https://m.facebook.com/'
                        ])
                        ->reactive()
                        ->suffixAction(
                            fn (?string $state, Closure $set): Action =>
                            Action::make('generate-fb')
                                ->icon('heroicon-o-plus')
                                ->action(function () use ($state, $set) {
                                    if (blank($state)) {
                                        $this->addError('facebook', 'Your profile link cannot be empty.');
                                        return;
                                    }
                                    $this->validateOnly('facebook');
                                    $set('facebook-code', 'TEST');
                                    $set('show_facebook_code', true);
                                }),
                        ),

                    Forms\Components\TextInput::make('facebook-code')
                        ->label('Verification code')
                        ->disabled()
                        ->visible(fn (Closure $get) => $get('facebook') && $this->show_facebook_code),

                    Forms\Components\Placeholder::make('facebook-account')
                        ->label('Our facebook account')
                        ->content('https://facebook.com/cotenanty'),

                    Forms\Components\Placeholder::make('submit-fb')
                        ->disableLabel()
                        ->content(self::getSubmitButton())
                        ->visible(fn (Closure $get) => $get('facebook') && $this->show_facebook_code)
                ])
                ->compact()
                ->collapsible()
        ];
    }

    protected function getInstagramFormSchema(): array
    {
        return [
            Section::make('Instagram')
                ->schema([
                    Forms\Components\TextInput::make('instagram')
                        ->label('Profile link')
                        ->placeholder('https://instagram.com/john_doe')
                        ->required()
                        ->activeUrl()
                        ->startsWith([
                            'https://instagram.com/',
                            'https://www.instagram.com/'
                        ])
                        ->reactive()
                        ->suffixAction(
                            fn (?string $state, Closure $set): Action =>
                            Action::make('generate-ig')
                                ->icon('heroicon-o-plus')
                                ->action(function () use ($state, $set) {
                                    if (blank($state)) {
                                        $this->addError('instagram', 'Your profile link cannot be empty.');
                                        return;
                                    }
                                    $this->validateOnly('instagram');
                                    $set('instagram-code', 'TEST');
                                    $set('show_instagram_code', true);
                                }),
                        ),

                    Forms\Components\TextInput::make('instagram-code')
                        ->label('Verification code')
                        ->disabled()
                        ->visible(fn (Closure $get) => $get('instagram') && $this->show_instagram_code),

                    Forms\Components\Placeholder::make('instagram-account')
                        ->label('Our Instagram account')
                        ->content('https://instagram.com/cotenanty'),

                    Forms\Components\Placeholder::make('submit-ig')
                        ->disableLabel()
                        ->content(self::getSubmitButton())
                        ->visible(fn (Closure $get) => $get('instagram') && $this->show_instagram_code)
                ])
                ->compact()
                ->collapsible()
        ];
    }

    protected function getTwitterFormSchema(): array
    {
        return [
            Section::make('Twitter')
                ->schema([
                    Forms\Components\TextInput::make('twitter')
                        ->label('Profile link')
                        ->placeholder('https://twitter.com/john_doe')
                        ->required()
                        ->activeUrl()
                        ->startsWith([
                            'https://twitter.com/',
                            'https://www.twitter.com/'
                        ])
                        ->reactive()
                        ->suffixAction(
                            fn (?string $state, Closure $set): Action =>
                            Action::make('generate-tw')
                                ->icon('heroicon-o-plus')
                                ->action(function () use ($state, $set) {
                                    if (blank($state)) {
                                        $this->addError('twitter', 'Your profile link cannot be empty.');
                                        return;
                                    }
                                    $this->validateOnly('twitter');
                                    $set('twitter-code', 'TEST');
                                    $set('show_twitter_code', true);
                                }),
                        ),

                    Forms\Components\TextInput::make('twitter-code')
                        ->label('Verification code')
                        ->disabled()
                        ->visible(fn (Closure $get) => $get('twitter') && $this->show_twitter_code),

                    Forms\Components\Placeholder::make('twitter-account')
                        ->label('Our twitter account')
                        ->content('https://twitter.com/cotenanty'),

                    Forms\Components\Placeholder::make('submit-tw')
                        ->disableLabel()
                        ->content(self::getSubmitButton())
                        ->visible(fn (Closure $get) => $get('twitter') && $this->show_twitter_code)
                ])
                ->compact()
                ->collapsible()
        ];
    }

    public function updateChannel(ContactChannelType|string $channelType)
    {
        $channelType = is_string($channelType) ? ContactChannelType::from($channelType) : $channelType;

        $data = $this->{$channelType->value . 'Form'}->getState();

        $this->getAuthUser()
            ->contactChannels()
            ->updateOrCreate(
                ['type' => $channelType->value],
                [
                    'link' => \trim($data[$channelType->value]),
                    'created_at' => \now(),
                    'updated_at' => \now()
                ]
            );

        Notification::make()
            ->title("Profile Link submitted succesfully")
            ->body("Your Profile Link has been submitted. Our team will review your Profile Link ASAP. Thanks!")
            ->success()
            ->seconds(15)
            ->send();
    }

    // public function updateWhatsapp()
    // {
    //     $data = $this->whatsappForm->getState();

    //     $this->getAuthUser()
    //         ->contactChannels()
    //         ->updateOrCreate(
    //             ['type' => ContactChannelType::WHATSAPP->value],
    //             [
    //                 'link' => \trim($data['whatsapp']),
    //                 'created_at' => \now(),
    //                 'updated_at' => \now()
    //             ]
    //         );

    //     Notification::make()
    //         ->title("Profile Link submitted succesfully")
    //         ->body("Your Profile Link has been submitted. Our team will review your Profile Link ASAP. Thanks!")
    //         ->success()
    //         ->seconds(15)
    //         ->send();
    // }

    // public function updateFacebook()
    // {
    //     $data = $this->facebookForm->getState();

    //     $this->getAuthUser()
    //         ->contactChannels()
    //         ->updateOrCreate(
    //             ['type' => ContactChannelType::FACEBOOK->value],
    //             [
    //                 'link' => \trim($data['facebook']),
    //                 'created_at' => \now(),
    //                 'updated_at' => \now()
    //             ]
    //         );

    //     Notification::make()
    //         ->title("Profile Link submitted succesfully")
    //         ->body("Your Profile Link has been submitted. Our team will review your Profile Link ASAP. Thanks!")
    //         ->success()
    //         ->seconds(15)
    //         ->send();
    // }

    // public function updateInstagram()
    // {
    //     $data = $this->instagramForm->getState();

    //     $this->getAuthUser()
    //         ->contactChannels()
    //         ->updateOrCreate(
    //             ['type' => ContactChannelType::INSTAGRAM->value],
    //             [
    //                 'link' => \trim($data['instagram']),
    //                 'created_at' => \now(),
    //                 'updated_at' => \now()
    //             ]            );

    //     Notification::make()
    //         ->title("Profile Link submitted succesfully")
    //         ->body("Your Profile Link has been submitted. Our team will review your Profile Link ASAP. Thanks!")
    //         ->success()
    //         ->seconds(15)
    //         ->send();
    // }

    // public function updateTwitter()
    // {
    //     $data = $this->twitterForm->getState();

    //     $this->getAuthUser()
    //         ->contactChannels()
    //         ->updateOrCreate(
    //             ['type' => ContactChannelType::TWITTER->value],
    //             [
    //                 'link' => \trim($data['twitter']),
    //                 'created_at' => \now(),
    //                 'updated_at' => \now()
    //             ]
    //         );

    //     Notification::make()
    //         ->title("Profile Link submitted succesfully")
    //         ->body("Your Profile Link has been submitted. Our team will review your Profile Link ASAP. Thanks!")
    //         ->success()
    //         ->seconds(15)
    //         ->send();
    // }

    public function canUpdateChannel(ContactChannelType|string $channelType)
    {
        $channel = $this->getChannel($channelType);

        if (blank($channel)) {
            return true;
        }

        // if your contact channel is successfully verified, you can only change it after one week
        if ($channel->isVerified() && $channel->created_at->lessThanOrEqualTo(now()->subWeek())) {
            return true;
        }

        // you can resubmit a new verification request if you've previously submitted a verification request
        // within the past 48 hours and it has not been verified
        if ($channel->isUnverified() && $channel->created_at->lessThanOrEqualTo(now()->subDays(2))) {
            return true;
        }

        return false;
    }

    public function getChannel(ContactChannelType|string $channelType)
    {
        $channelType = is_string($channelType) ? ContactChannelType::from($channelType) : $channelType;

        return $this->getAuthUser()
            ->getLatestContactChannelByType($channelType);
    }

    public static function getSubmitButton(): HtmlString
    {
        return new HtmlString(Blade::render("
        <x-filament::button size='sm' type='submit' size='sm' style='font-weight: 600;'>
            {{ __('Submit For Verification') }}
        </x-filament::button>"));
    }

    protected function getForms(): array
    {
        return [
            'whatsappForm' => $this->makeForm()
                ->schema($this->getWhatsappFormSchema()),
            'facebookForm' => $this->makeForm()
                ->schema($this->getFacebookFormSchema()),
            'instagramForm' => $this->makeForm()
                ->schema($this->getInstagramFormSchema()),
            'twitterForm' => $this->makeForm()
                ->schema($this->getTwitterFormSchema()),
        ];
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.contact-channels-settings-page');

        return $view->layout('layouts.guest');
    }
}
