<?php

namespace App\Filament\Resources;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ContactChannelResource\Pages\ListContactChannels;
use App\Filament\Resources\ContactChannelResource\Pages\CreateContactChannel;
use App\Filament\Resources\ContactChannelResource\Pages\EditContactChannel;
use App\Enums\ContactChannelType;
use App\Filament\Resources\ContactChannelResource\Pages;
use App\Models\ContactChannel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactChannelResource extends Resource
{
    protected static ?string $model = ContactChannel::class;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-link';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Connections';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'full_name')
                    ->searchable()
                    ->required(),
                Select::make('type')
                    ->options(self::getTypeOptions())
                    ->required(),
                TextInput::make('link')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_enabled')
                    ->label('Enabled'),
                DateTimePicker::make('verified_at')
                    ->label('Verified At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.full_name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(static fn ($state) => self::getTypeOptions()[$state] ?? $state),
                TextColumn::make('link')
                    ->searchable(),
                IconColumn::make('is_enabled')
                    ->boolean()
                    ->label('Enabled'),
                TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(self::getTypeOptions()),
                TernaryFilter::make('verified_at')
                    ->label('Verified')
                    ->nullable(),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(static fn (ContactChannel $record): bool => blank($record->verified_at))
                    ->action(static function (ContactChannel $record): void {
                        $record->forceFill(['verified_at' => now()])->save();
                    }),
                Action::make('unverify')
                    ->label('Unverify')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(static fn (ContactChannel $record): bool => filled($record->verified_at))
                    ->action(static function (ContactChannel $record): void {
                        $record->forceFill(['verified_at' => null])->save();
                    }),
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ListContactChannels::route('/'),
            'create' => CreateContactChannel::route('/create'),
            'edit' => EditContactChannel::route('/{record}/edit'),
        ];
    }

    protected static function getTypeOptions(): array
    {
        return [
            ContactChannelType::WHATSAPP->value => 'Whatsapp',
            ContactChannelType::FACEBOOK->value => 'Facebook',
            ContactChannelType::INSTAGRAM->value => 'Instagram',
            ContactChannelType::TWITTER->value => 'Twitter',
            ContactChannelType::EMAIL->value => 'Email',
        ];
    }
}
