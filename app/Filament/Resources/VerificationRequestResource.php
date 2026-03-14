<?php

namespace App\Filament\Resources;

use App\Enums\VerificationStatus;
use App\Filament\Resources\VerificationRequestResource\Pages;
use App\Models\User;
use App\Notifications\IdentityVerificationStatusNotification;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VerificationRequestResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Verification Requests';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('verification_status', VerificationStatus::PENDING);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('verification_submitted_at')
                    ->label('Date Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (User $record): string => 'Review ' . $record->full_name)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (User $record) => view(
                        'filament.resources.verification-request-resource.review-files',
                        [
                            'identityUrl' => self::signedFileUrl($record, 'identity'),
                            'selfieUrl' => self::signedFileUrl($record, 'selfie'),
                        ],
                    )),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->forceFill([
                            'verification_status' => VerificationStatus::APPROVED,
                            'rejection_reason' => null,
                        ])->save();

                        $record->notify(new IdentityVerificationStatusNotification(VerificationStatus::APPROVED));

                        Notification::make()
                            ->title('Verification approved.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000),
                    ])
                    ->action(function (User $record, array $data): void {
                        $disk = Storage::disk('kyc_private');

                        foreach ([$record->identity_document_path, $record->selfie_path] as $path) {
                            if (filled($path) && $disk->exists($path)) {
                                $disk->delete($path);
                            }
                        }

                        $record->forceFill([
                            'verification_status' => VerificationStatus::REJECTED,
                            'rejection_reason' => $data['rejection_reason'],
                            'identity_document_path' => null,
                            'selfie_path' => null,
                            'verification_submitted_at' => null,
                        ])->save();

                        $record->notify(new IdentityVerificationStatusNotification(
                            VerificationStatus::REJECTED,
                            $data['rejection_reason'],
                        ));

                        Notification::make()
                            ->title('Verification rejected.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('suspend')
                    ->label('Suspend Account')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->form([
                        Textarea::make('suspension_reason')
                            ->label('Suspension Reason')
                            ->required()
                            ->rows(4)
                            ->maxLength(1000),
                    ])
                    ->visible(fn (User $record): bool => !$record->isSuspended())
                    ->action(function (User $record, array $data): void {
                        $record->suspend($data['suspension_reason']);

                        Notification::make()
                            ->title('Account suspended.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('unsuspend')
                    ->label('Unsuspend Account')
                    ->icon('heroicon-o-lock-open')
                    ->color('gray')
                    ->visible(fn (User $record): bool => $record->isSuspended())
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->unsuspend();

                        Notification::make()
                            ->title('Account unsuspended.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerificationRequests::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    protected static function signedFileUrl(User $record, string $type): ?string
    {
        $path = $type === 'identity' ? $record->identity_document_path : $record->selfie_path;

        if (blank($path)) {
            return null;
        }

        return URL::temporarySignedRoute(
            'admin.verification-file.show',
            now()->addMinutes(30),
            ['user' => $record, 'type' => $type],
        );
    }
}

