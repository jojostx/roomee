<?php

namespace App\Http\Livewire\Pages\Settings;

use App\Http\Livewire\Components\Filament\Forms\Password as PasswordFormComponent;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Ysfkaya\FilamentPhoneInput\PhoneInput;

class AccountSettingsPage extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public User $authUser;

    public $savedPersonalInfo = false;
    public $savedContactInfo = false;
    public $savedPasswordInfo = false;
    public $savedBankAccountInfo = false;

    public $first_name;
    public $middle_name;

    public $email;
    public $phone_number;
    public $state;
    public $city;
    public $address;
    public $postcode;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public $account_name;
    public $account_number;
    public $bank_name;

    protected function getFormModel(): ?User
    {
        return Auth::user();
    }

    protected array $messages = [
        'current_password' => 'The provided password does not match your current password',
    ];

    public function mount()
    {
        $authUser = $this->getFormModel();

        $this->personalInfoForm->fill([
            'first_name' => $authUser->first_name,
            'last_name' => $authUser->last_name,
        ]);

        $this->contactInfoForm->fill([
            'email' => $authUser->email,
            'phone_number' => $authUser->phone_number,
        ]);

        $this->passwordInfoForm->fill([
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);
    }

    protected function getPersonalInfoFormSchema(): array
    {
        return [
            Forms\Components\Grid::make([
                'default' => 1,
                'md' => 2,
            ])
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
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
            Forms\Components\Grid::make([
                'default' => 1,
            ])
            ->schema([
                Forms\Components\TextInput::make('email')
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
            Forms\Components\Grid::make([
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
                Forms\Components\Grid::make()
                    ->schema([
                        PasswordFormComponent::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->confirmed()
                            ->maxValue(100)
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

    protected function getForms(): array
    {
        $authUser = $this->getFormModel();

        return [
            'personalInfoForm' => $this->makeForm()
                ->schema($this->getPersonalInfoFormSchema())
                ->model($authUser),

            'contactInfoForm' => $this->makeForm()
                ->schema($this->getContactInfoFormSchema())
                ->model($authUser),

            'passwordInfoForm' => $this->makeForm()
                ->schema($this->getPasswordInfoFormSchema())
                ->model($authUser),
        ];
    }

    public function savePersonalInfo(): void
    {
        $saved = $this->getFormModel()->fill($this->personalInfoForm->getState())->save();

        $saved && $this->showSuccessNotification('Details saved successfully.');
    }

    public function saveContactInfo(): void
    {
        try {
            $saved_attributes = DB::transaction(function () {
                $authUser = $this->getFormModel();
                $saved_attributes = ['email' => false];

                $email = $this->contactInfoForm->getState()['email'];

                if (filled($email)) {
                    $saved_attributes['email'] = filled($authUser->newEmail($email));
                }
                return $saved_attributes;
            });

            if ($saved_attributes['email']) {
                $this->showSuccessNotification('Follow the instructions in the mail sent to the email address you provided to add the new email.');
            }
        } catch (\Throwable $th) {
            $this->showFailureNotification('Unable to save details. Try again later');
        }
    }

    public function savePasswordInfo(): void
    {
        $authUser = $this->getFormModel();

        $saved =  $authUser->forceFill([
            'password' => Hash::make($this->passwordInfoForm->getState()['new_password']),
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
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.account-settings-page');

        return $view->layout('layouts.guest');
    }
}
