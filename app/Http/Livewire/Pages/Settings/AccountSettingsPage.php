<?php

namespace App\Http\Livewire\Pages\Settings;

use App\Models\User;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AccountSettingsPage extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected function getFormModel(): ?User
    {
        return Auth::user();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('General')
                ->collapsible()
                ->description(str('<span class="text-sm md:text-base">You can add a Phone number, change you Email and Password here<span>')->toHtmlString())
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->helperText('To change the Email associated with your account, you will have to verify the new Email')
                        ->required()
                        ->placeholder('example@gmail.com')
                        ->email(),
                    Forms\Components\TextInput::make('current_password')
                        ->disabled()
                        ->default(function () {
                            return '$this->getFormModel()->password';
                        }),
                    Forms\Components\TextInput::make('password')->password()->confirmed()->required(),
                    Forms\Components\TextInput::make('password_confirmation')->password()->required(),
                ]),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();
    }


    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.account-settings-page');

        return $view->layout('layouts.guest');
    }
}
