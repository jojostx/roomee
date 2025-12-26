<?php

namespace App\Filament\Resources;

use App\Enums\ContactChannelType;
use App\Filament\Resources\ContactChannelResource\Pages;
use App\Models\ContactChannel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactChannelResource extends Resource
{
    protected static ?string $model = ContactChannel::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Connections';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'full_name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(self::getTypeOptions())
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('Enabled'),
                Forms\Components\DateTimePicker::make('verified_at')
                    ->label('Verified At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(static fn ($state) => self::getTypeOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('link')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->boolean()
                    ->label('Enabled'),
                Tables\Columns\TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(self::getTypeOptions()),
                Tables\Filters\TernaryFilter::make('verified_at')
                    ->label('Verified')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-badge-check')
                    ->color('success')
                    ->visible(static fn (ContactChannel $record): bool => blank($record->verified_at))
                    ->action(static function (ContactChannel $record): void {
                        $record->forceFill(['verified_at' => now()])->save();
                    }),
                Tables\Actions\Action::make('unverify')
                    ->label('Unverify')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(static fn (ContactChannel $record): bool => filled($record->verified_at))
                    ->action(static function (ContactChannel $record): void {
                        $record->forceFill(['verified_at' => null])->save();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListContactChannels::route('/'),
            'create' => Pages\CreateContactChannel::route('/create'),
            'edit' => Pages\EditContactChannel::route('/{record}/edit'),
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
