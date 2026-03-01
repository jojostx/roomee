<?php

namespace App\Filament\Pages;

use App\Services\Settings\VerificationTimelineSettings;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class VerificationSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Verification Settings';

    protected static string $view = 'filament.pages.verification-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'timeline_min_hours' => VerificationTimelineSettings::getMinHours(),
            'timeline_max_hours' => VerificationTimelineSettings::getMaxHours(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pending Verification Timeline')
                    ->description('Configure the review timeline shown to users while verification is pending.')
                    ->schema([
                        Forms\Components\TextInput::make('timeline_min_hours')
                            ->label('Minimum Hours')
                            ->required()
                            ->numeric()
                            ->minValue(1),

                        Forms\Components\TextInput::make('timeline_max_hours')
                            ->label('Maximum Hours')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->gte('timeline_min_hours'),

                        Forms\Components\Placeholder::make('current_display')
                            ->label('Current Display')
                            ->content(fn () => VerificationTimelineSettings::getDisplayText()),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        VerificationTimelineSettings::set(
            intval($state['timeline_min_hours']),
            intval($state['timeline_max_hours']),
        );

        $this->form->fill([
            'timeline_min_hours' => VerificationTimelineSettings::getMinHours(),
            'timeline_max_hours' => VerificationTimelineSettings::getMaxHours(),
        ]);

        Notification::make()
            ->title('Verification settings saved.')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

