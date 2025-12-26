<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use App\Enums\RoommateRequestStatus;
use App\Enums\UserRole;
use App\Services\Sms\SmsSender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class MessageUsers extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Communications';

    protected static ?string $navigationLabel = 'Message Users';

    protected static string $view = 'filament.pages.message-users';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'send_to_all' => false,
            'send_email' => true,
            'send_sms' => false,
            'filter_verified' => 'any',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Recipients')
                    ->schema([
                        Forms\Components\Toggle::make('send_to_all')
                            ->label('Send to all users')
                            ->live(),
                        Forms\Components\Select::make('recipients')
                            ->label('Recipients')
                            ->multiple()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                return User::query()
                                    ->where('email', 'like', "%{$search}%")
                                    ->orWhere('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn (User $user) => [$user->id => "{$user->full_name} ({$user->email})"])
                                    ->all();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $user = User::find($value);

                                return $user ? "{$user->full_name} ({$user->email})" : null;
                            })
                            ->required(fn (Get $get) => ! $get('send_to_all'))
                            ->visible(fn (Get $get) => ! $get('send_to_all')),
                        Forms\Components\Select::make('filter_roles')
                            ->label('Filter by Role')
                            ->options(UserRole::labels())
                            ->multiple()
                            ->searchable()
                            ->visible(fn (Get $get) => (bool) $get('send_to_all')),
                        Forms\Components\Select::make('filter_verified')
                            ->label('Filter by Verified Status')
                            ->options([
                                'any' => 'Any',
                                'verified' => 'Verified only',
                                'unverified' => 'Unverified only',
                            ])
                            ->visible(fn (Get $get) => (bool) $get('send_to_all')),
                        Forms\Components\Select::make('filter_request_statuses')
                            ->label('Filter by Request Status')
                            ->options(self::getRequestStatusOptions())
                            ->multiple()
                            ->visible(fn (Get $get) => (bool) $get('send_to_all')),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->required(fn (Get $get) => (bool) $get('send_email'))
                            ->maxLength(150),
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->rows(6),
                        Forms\Components\Toggle::make('send_email')
                            ->label('Send Email')
                            ->inline(),
                        Forms\Components\Toggle::make('send_sms')
                            ->label('Send SMS')
                            ->inline(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        if (! ($data['send_email'] ?? false) && ! ($data['send_sms'] ?? false)) {
            Notification::make()
                ->title('Select at least one delivery channel.')
                ->danger()
                ->send();

            return;
        }

        if ($data['send_to_all'] ?? false) {
            $query = User::query();
            $roles = $data['filter_roles'] ?? [];

            if (! empty($roles)) {
                $query->whereIn('role', $roles);
            }

            $verified = $data['filter_verified'] ?? 'any';

            if ($verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($verified === 'unverified') {
                $query->whereNull('email_verified_at');
            }

            $requestStatuses = $data['filter_request_statuses'] ?? [];

            if (! empty($requestStatuses)) {
                $query->where(function ($builder) use ($requestStatuses): void {
                    $builder->whereHas('sentRoommateRequests', function ($inner) use ($requestStatuses): void {
                        $inner->whereIn('status', $requestStatuses);
                    })->orWhereHas('receivedRoommateRequests', function ($inner) use ($requestStatuses): void {
                        $inner->whereIn('status', $requestStatuses);
                    });
                });
            }

            $users = $query->get();
        } else {
            $users = User::whereKey($data['recipients'] ?? [])->get();
        }

        if ($users->isEmpty()) {
            Notification::make()
                ->title('No recipients selected.')
                ->danger()
                ->send();

            return;
        }

        $emailCount = 0;
        $smsCount = 0;

        if ($data['send_email'] ?? false) {
            NotificationFacade::send($users, new AdminBroadcastNotification(
                $data['subject'],
                $data['message']
            ));
            $emailCount = $users->count();
        }

        if ($data['send_sms'] ?? false) {
            $sender = app(SmsSender::class);

            foreach ($users as $user) {
                $phone = $user->getSmsNumber();

                if (! $phone) {
                    continue;
                }

                if ($sender->send($phone, $data['message'])) {
                    $smsCount++;
                }
            }
        }

        Notification::make()
            ->title('Messages sent')
            ->body("Email: {$emailCount}, SMS: {$smsCount}")
            ->success()
            ->send();
    }

    protected static function getRequestStatusOptions(): array
    {
        return [
            RoommateRequestStatus::PENDING->value => 'Pending',
            RoommateRequestStatus::ACCEPTED->value => 'Accepted',
            RoommateRequestStatus::DENIED->value => 'Denied',
            RoommateRequestStatus::DELETED->value => 'Deleted',
        ];
    }
}
