<?php

namespace App\Http\Livewire\Pages\Settings;

use App\Enums\ContactChannelType;
use App\Models\ContactChannel;
use App\Models\User;
use Closure;
use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

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
    public Collection $channels;

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
        $this->getChannels();

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

    protected function getChannels()
    {
        $this->channels = $this->getAuthUser()->getLatestContactChannels();
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

                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\ViewField::make('cancel')
                                ->view('livewire.components.filament.forms.cancel-update-button')
                                ->id('whatsapp')
                                ->visible(fn ()  => filled($this->getChannel('whatsapp')))
                                ->columnSpan(1),

                            Forms\Components\Placeholder::make('submit-whatsapp')
                                ->columnSpan(2)
                                ->extraAttributes(['class' => 'flex justify-end'])
                                ->disableLabel()
                                ->content(self::getSubmitButton())
                                ->visible(fn (Closure $get) => $get('whatsapp') && $this->{'show_whatsapp_code'}),
                        ])->columns([
                            'default' => 3,
                            'sm' => 3,
                            'md' => 3,
                            'lg' => 3
                        ]),
                ])
                ->compact()
                ->collapsible()
        ];
    }

    protected function getFacebookFormSchema(): array
    {
        return [
            Section::make('Facebook')
                ->schema(fn () => $this->getFormSchema(
                    'facebook',
                    [
                        'link_placeholder' => 'ex: https://facebook.com/John.doe',
                        'startsWith' => [
                            'https://www.facebook.com/',
                            'https://facebook.com/',
                            'https://web.facebook.com/',
                            'https://m.facebook.com/'
                        ],
                        'account_placeholder_content' => 'https://facebook.com/cotenanty'
                    ]
                ))
                ->compact()
                ->collapsible()
        ];
    }

    protected function getInstagramFormSchema(): array
    {
        return [
            Section::make('instagram')
                ->schema(fn () => $this->getFormSchema(
                    'instagram',
                    [
                        'link_placeholder' => 'ex: https://instagram.com/john_doe',
                        'startsWith' => [
                            'https://instagram.com/',
                            'https://www.instagram.com/'
                        ],
                        'account_placeholder_content' => 'https://instagram.com/cotenanty'
                    ]
                ))
                ->compact()
                ->collapsible()
        ];
    }

    protected function getTwitterFormSchema(): array
    {
        return [
            Section::make('twitter')
                ->schema(fn () => $this->getFormSchema(
                    'twitter',
                    [
                        'link_placeholder' => 'ex: https://twitter.com/john_doe',
                        'startsWith' => [
                            'https://twitter.com/',
                            'https://www.twitter.com/'
                        ],
                        'account_placeholder_content' => 'https://twitter.com/cotenanty'
                    ]
                ))
                ->compact()
                ->collapsible()
        ];
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

    protected function getFormSchema(string $channel, array $data)
    {
        return [
            Forms\Components\TextInput::make($channel)
                ->label('Profile link')
                ->placeholder($data['link_placeholder'])
                ->required()
                ->activeUrl()
                ->startsWith($data['startsWith'])
                ->reactive()
                ->suffixAction(
                    fn (?string $state, Closure $set): Action =>
                    Action::make('generate-' . $channel)
                        ->icon('heroicon-o-plus')
                        ->action(function () use ($state, $set, $channel) {
                            if (blank($state)) {
                                $this->addError($channel, 'Your profile link cannot be empty.');
                                return;
                            }
                            $this->validateOnly($channel);
                            $set($channel . '-code', 'TEST');
                            $set('show_' . $channel . '_code', true);
                        }),
                ),

            Forms\Components\TextInput::make($channel . '-code')
                ->label('Verification code')
                ->disabled()
                ->visible(fn (Closure $get) => $get($channel) && $this->{'show_' . $channel . '_code'}),

            Forms\Components\Placeholder::make($channel . '-account')
                ->label('Our ' . $channel . ' account')
                ->content($data['account_placeholder_content']),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\ViewField::make('cancel')
                        ->view('livewire.components.filament.forms.cancel-update-button')
                        ->id($channel)
                        ->visible(fn ()  => filled($this->getChannel($channel)))
                        ->columnSpan(1),

                    Forms\Components\Placeholder::make('submit-' . $channel)
                        ->columnSpan(2)
                        ->extraAttributes(['class' => 'flex justify-end'])
                        ->disableLabel()
                        ->content(self::getSubmitButton())
                        ->visible(fn (Closure $get) => $get($channel) && $this->{'show_' . $channel . '_code'}),
                ])->columns([
                    'default' => 3,
                    'sm' => 3,
                    'md' => 3,
                    'lg' => 3
                ])
        ];
    }

    protected static function getSubmitButton(): HtmlString
    {
        return new HtmlString(Blade::render("
        <x-filament::button size='sm' type='submit' size='sm' style='font-weight: 600;'>
            {{ __('Submit For Verification') }}
        </x-filament::button>"));
    }

    public function getChannelNamesProperty()
    {
        return ContactChannelType::getChannelNames(['email']);
    }

    public function getChannel(ContactChannelType|string $channelType)
    {
        $channelType = is_string($channelType) ? ContactChannelType::from($channelType) : $channelType;

        return $this->channels->firstWhere('type', $channelType->value);
    }

    public function showUpdatedChannelComponent(ContactChannel|string $channel)
    {
        $channel = \is_string($channel) ? $this->getChannel($channel) : $channel;

        if (blank($channel)) {
            return false;
        }

        // if your contact channel is successfully verified, you can only change it after one week
        return $channel->isVerified() && $channel->verified_at->lessThanOrEqualTo(now()->subWeek());
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
                    'updated_at' => \now(),
                    'verified_at' => null
                ]
            );

        Notification::make()
            ->title("Profile Link submitted succesfully")
            ->body("Your Profile Link has been submitted. Our team will review your Profile Link ASAP. Thanks!")
            ->success()
            ->seconds(15)
            ->send();
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.contact-channels-settings-page');

        return $view->layout('layouts.guest');
    }
}

// 1. if the user has submitted a contact channel and it is unverified and it was submitted within the past week
//     disable update and show restricted-card
// 2. if the user has submitted a contact channel and it is verified and it was submitted within the past week
//     disable update and show updated-card with the ability to update contact channel disabled
// 3. if the user has submitted a contact channel and it is verified and it was not submitted within the past week
//     enabled update and show updated-card
// 4. if the user has not submitted a contact channel
//     show the update form 