<?php

namespace App\Filament\Resources;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
  {
      return 'heroicon-o-users';
  }

  public static function getNavigationGroup(): string|UnitEnum|null
  {
      return 'User Management';
  }

  public static function form(Schema $schema): Schema
  {
    return $schema
      ->components([
        Section::make('Profile')
          ->schema([
            TextInput::make('first_name')
              ->required()
              ->maxLength(255),
            TextInput::make('last_name')
              ->required()
              ->maxLength(255),
            TextInput::make('email')
              ->email()
              ->required()
              ->maxLength(255),
            Select::make('role')
              ->options(UserRole::labels())
              ->required(),
            Toggle::make('email_verified_at')
              ->label('Email Verified')
              ->onColor('success')
              ->offColor('danger')
              ->afterStateHydrated(static function (Toggle $component, $state): void {
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
        TextColumn::make('full_name')
          ->label('Name')
          ->searchable(['first_name', 'last_name'])
          ->sortable(),
        TextColumn::make('email')
          ->searchable()
          ->sortable(),
        TextColumn::make('role')
          ->badge()
          ->formatStateUsing(static function ($state): string {
            if ($state instanceof UserRole) {
              return UserRole::labels()[$state->value] ?? $state->value;
            }

            return UserRole::labels()[$state] ?? (string) $state;
          }),
        IconColumn::make('email_verified_at')
          ->label('Verified')
          ->boolean()
          ->sortable(),
        TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(),
      ])
      ->filters([
        SelectFilter::make('role')
          ->options(UserRole::labels()),
        TernaryFilter::make('email_verified_at')
          ->label('Email Verified')
          ->nullable(),
      ])
      ->recordActions([
        Action::make('verify_email')
          ->label('Mark Verified')
          ->icon('heroicon-o-check-badge')
          ->color('success')
          ->visible(static fn(User $record): bool => blank($record->email_verified_at))
          ->action(static function (User $record): void {
            $record->forceFill(['email_verified_at' => now()])->save();
          }),
        Action::make('unverify_email')
          ->label('Mark Unverified')
          ->icon('heroicon-o-x-circle')
          ->color('danger')
          ->visible(static fn(User $record): bool => filled($record->email_verified_at))
          ->action(static function (User $record): void {
            $record->forceFill(['email_verified_at' => null])->save();
          }),
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index' => ListUsers::route('/'),
      'create' => CreateUser::route('/create'),
      'edit' => EditUser::route('/{record}/edit'),
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
