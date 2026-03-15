<?php

namespace App\Livewire\Pages\Settings;

use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Throwable;
use Illuminate\View\View;
use App\Enums\BudgetLimit;
use App\Livewire\Components\Filament\Forms\Password as PasswordFormComponent;
use App\Models\Listing;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class AccountSettingsPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    /** @var array<string, mixed> */
    public array $personalData = [];
    /** @var array<string, mixed> */
    public array $contactData = [];
    /** @var array<string, mixed> */
    public array $passwordData = [];
    /** @var array<string, mixed> */
    public array $matchingData = [];
    protected function getFormModel(): ?User
    {
        return Auth::user();
    }
    protected function getAuthUserOrFail(): User
    {
        $authUser = $this->getFormModel();

        if (! $authUser) {
            throw new AuthenticationException();
        }

        return $authUser;
    }
    protected array $messages = [
        'current_password' => 'The provided password does not match your current password',
    ];
    public function mount()
    {
        $authUser = $this->getAuthUserOrFail();

        $this->personalInfoForm->fill([
            'first_name' => $authUser->first_name,
            'last_name' => $authUser->last_name,
        ]);

        $this->contactInfoForm->fill([
            'email' => $authUser->email,
        ]);

        $this->passwordInfoForm->fill([
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);

        $this->matchingPreferencesForm->fill($this->getMatchingPreferencesState($authUser));
    }
    /**
     * @return array<string, mixed>
     */
    protected function getMatchingPreferencesState(User $authUser): array
    {
        $listingPreferences = $authUser->getListingDiscoveryPreferences();

        return [
            'strict_gender_filter' => $authUser->isGenderSpecificFilteringEnabled(),
            'listing_budget_min' => $listingPreferences['budget_min'],
            'listing_budget_max' => $listingPreferences['budget_max'],
            'listing_move_in_date' => $listingPreferences['move_in_date'],
            'listing_dealbreakers' => $listingPreferences['dealbreakers'],
        ];
    }
    /**
     * @param  mixed  $dealbreakers
     * @return array<int, string>
     */
    protected function sanitizeDealbreakers(mixed $dealbreakers): array
    {
        if (! is_array($dealbreakers)) {
            return [];
        }

        return array_values(array_intersect(
            array_map(static fn (mixed $value): string => (string) $value, $dealbreakers),
            array_keys(Listing::DEALBREAKER_OPTIONS)
        ));
    }
    protected function getPersonalInfoFormSchema(): array
    {
        return [
            Grid::make([
                'default' => 1,
                'md' => 2,
            ])
                ->schema([
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('last_name')
                        ->label('Last Name')
                        ->required()
                        ->maxLength(255)
                        ->string(),
                ]),
        ];
    }
    protected function getContactInfoFormSchema(): array
    {
        return [
            Grid::make([
                'default' => 1,
            ])
            ->schema([
                TextInput::make('email')
                    ->label('Email Address')
                    ->helperText(
                        'To change your email you need to verify the new email address that you provide.
                        An email containing verification instructions will be sent to the provided email address.'
                    )
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique('pending_user_emails', 'email')
                    ->unique('users', 'email', $this->getFormModel()),
            ]),
        ];
    }
    protected function getPasswordInfoFormSchema(): array
    {
        return [
            Grid::make([
                'default' => 1,
                'md' => 2,
            ])->schema([
                PasswordFormComponent::make('current_password')
                    ->label('Current Password')
                    ->password()
                    ->required()
                    ->requiredWith('new_password')
                    ->currentPassword()
                    ->autocomplete('off')
                    ->columnSpan(1),
                Grid::make()
                    ->schema([
                        PasswordFormComponent::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->confirmed()
                            ->maxLength(100)
                            ->rule(Password::defaults())
                            ->generatable(),
                        PasswordFormComponent::make('new_password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->requiredWith('new_password')
                            ->dehydrated(false),
                    ]),
            ]),
        ];
    }
    protected function getMatchingPreferencesFormSchema(): array
    {
        return [
            Section::make('Discovery Preferences')
                ->description('Control gender-specific matching visibility for discovery and recommendations.')
                ->schema([
                    Toggle::make('strict_gender_filter')
                        ->label('Strict gender-specific filtering')
                        ->helperText('When enabled, you only see users of your gender, and only users of your gender can discover your profile.')
                        ->default(true)
                        ->inline(false),
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])->schema([
                        Select::make('listing_budget_min')
                            ->label('Listing Budget Min')
                            ->placeholder('Use profile minimum budget')
                            ->options(BudgetLimit::budgetRangeAssoc())
                            ->searchable(),
                        Select::make('listing_budget_max')
                            ->label('Listing Budget Max')
                            ->placeholder('Use profile maximum budget')
                            ->options(BudgetLimit::budgetRangeAssoc())
                            ->gte('listing_budget_min')
                            ->searchable(),
                    ]),
                    DatePicker::make('listing_move_in_date')
                        ->label('Latest Move-in Date')
                        ->helperText('Only listings with move-in date on or before this date will be shown.')
                        ->native(false)
                        ->minDate(now()->toDateString()),
                    CheckboxList::make('listing_dealbreakers')
                        ->label('Dealbreakers')
                        ->options(Listing::DEALBREAKER_OPTIONS)
                        ->helperText('Listings failing selected dealbreakers are filtered out.')
                        ->columns(1),
                ]),
        ];
    }
    protected function getForms(): array
    {
        $authUser = $this->getAuthUserOrFail();

        return [
            'personalInfoForm' => $this->makeSchema()
                ->schema($this->getPersonalInfoFormSchema())
                ->model($authUser)
                ->statePath('personalData'),

            'contactInfoForm' => $this->makeSchema()
                ->schema($this->getContactInfoFormSchema())
                ->model($authUser)
                ->statePath('contactData'),

            'passwordInfoForm' => $this->makeSchema()
                ->schema($this->getPasswordInfoFormSchema())
                ->model($authUser)
                ->statePath('passwordData'),

            'matchingPreferencesForm' => $this->makeSchema()
                ->schema($this->getMatchingPreferencesFormSchema())
                ->model($authUser)
                ->statePath('matchingData'),
        ];
    }
    public function savePersonalInfo(): void
    {
        $saved = $this->getAuthUserOrFail()
            ->fill($this->personalInfoForm->getState())
            ->save();

        if ($saved) {
            $this->showSuccessNotification('Details saved successfully.');
        }
    }
    public function saveContactInfo(): void
    {
        try {
            $authUser = $this->getAuthUserOrFail();
            $state = $this->contactInfoForm->getState();
            $email = $state['email'] ?? null;

            $savedEmail = false;

            if (filled($email)) {
                $savedEmail = DB::transaction(fn (): bool => filled($authUser->newEmail((string) $email)));
            }

            if ($savedEmail) {
                $this->showSuccessNotification('Follow the instructions in the mail sent to the email address you provided to add the new email.');
            }
        } catch (Throwable $th) {
            report($th);
            $this->showFailureNotification('Unable to save details. Try again later');
        }
    }
    public function savePasswordInfo(): void
    {
        $authUser = $this->getAuthUserOrFail();
        $passwordData = $this->passwordInfoForm->getState();

        $saved =  $authUser->forceFill([
            'password' => Hash::make((string) ($passwordData['new_password'] ?? '')),
            'remember_token' => str()->random(60),
        ])->save();

        if ($saved) {
            Auth::guard('web')->logout();
            session()->invalidate();
            session()->regenerateToken();

            Auth::guard('web')->login($authUser);

            event(new PasswordReset($authUser));
            $this->showSuccessNotification('Password updated successfully.');
        }
    }
    public function saveMatchingPreferences(): void
    {
        $authUser = $this->getAuthUserOrFail();
        $state = $this->matchingPreferencesForm->getState();
        $selectedDealbreakers = $this->sanitizeDealbreakers($state['listing_dealbreakers'] ?? []);

        $savedGenderPreference = $authUser->updateGenderSpecificFiltering((bool) ($state['strict_gender_filter'] ?? true));
        $savedListingPreferences = $authUser->updateListingDiscoveryPreferences([
            'budget_min' => $state['listing_budget_min'] ?? null,
            'budget_max' => $state['listing_budget_max'] ?? null,
            'move_in_date' => $state['listing_move_in_date'] ?? null,
            'dealbreakers' => $selectedDealbreakers,
        ]);

        if ($savedGenderPreference && $savedListingPreferences) {
            $this->showSuccessNotification('Discovery preference updated successfully.');
            return;
        }

        $this->showFailureNotification('Unable to update discovery preference. Try again later.');
    }
    protected function showSuccessNotification(string|Closure|null $body)
    {
        Notification::make('save-success-' . str()->random(5))
            ->body($body)
            ->success()
            ->seconds(10)
            ->send();
    }
    protected function showFailureNotification(string|Closure|null $body)
    {
        Notification::make('save-failed-' . str()->random(5))
            ->body($body)
            ->danger()
            ->seconds(10)
            ->send();
    }
    public function render()
    {
        /** @var View */
        $view = view('livewire.pages.settings.account-settings-page');

        return $view->layout('layouts.guest');
    }
}

