<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';

  protected static ?string $navigationGroup = 'User Management';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Profile')
          ->schema([
            Forms\Components\TextInput::make('first_name')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('email')
              ->email()
              ->required()
              ->maxLength(255),
            Forms\Components\Select::make('role')
              ->options(UserRole::labels())
              ->required(),
            Forms\Components\Toggle::make('email_verified_at')
              ->label('Email Verified')
              ->onColor('success')
              ->offColor('danger')
              ->afterStateHydrated(static function (Forms\Components\Toggle $component, $state): void {
                $component->state(filled($state));
              })
              ->dehydrateStateUsing(static fn($state) => $state ? now() : null),
          ])
          ->columns(2),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('full_name')
          ->label('Name')
          ->searchable(['first_name', 'last_name'])
          ->sortable(),
        Tables\Columns\TextColumn::make('email')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('role')
          ->badge()
          ->formatStateUsing(static function ($state): string {
            if ($state instanceof UserRole) {
              return UserRole::labels()[$state->value] ?? $state->value;
            }

            return UserRole::labels()[$state] ?? (string) $state;
          }),
        Tables\Columns\IconColumn::make('email_verified_at')
          ->label('Verified')
          ->boolean()
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('role')
          ->options(UserRole::labels()),
        Tables\Filters\TernaryFilter::make('email_verified_at')
          ->label('Email Verified')
          ->nullable(),
      ])
      ->actions([
        Tables\Actions\Action::make('verify_email')
          ->label('Mark Verified')
          ->icon('heroicon-o-check-badge')
          ->color('success')
          ->visible(static fn(User $record): bool => blank($record->email_verified_at))
          ->action(static function (User $record): void {
            $record->forceFill(['email_verified_at' => now()])->save();
          }),
        Tables\Actions\Action::make('unverify_email')
          ->label('Mark Unverified')
          ->icon('heroicon-o-x-circle')
          ->color('danger')
          ->visible(static fn(User $record): bool => filled($record->email_verified_at))
          ->action(static function (User $record): void {
            $record->forceFill(['email_verified_at' => null])->save();
          }),
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }

  // This ensures only admins see the navigation item
  public static function canViewAny(): bool
  {
    return auth()->check() && auth()->user()->isAdmin();
  }

  // Additional security: prevent admins from deleting themselves
  public static function canDelete(Model $record): bool
  {
    // Don't allow deleting yourself
    if (auth()->id() === $record->id) {
      return false;
    }

    return auth()->user()->isAdmin();
  }

  // Prevent editing your own role (optional security measure)
  public static function canEdit(Model $record): bool
  {
    return auth()->user()->isAdmin();
  }
}
