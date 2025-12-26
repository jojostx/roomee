<?php

namespace App\Filament\Resources;

use App\Enums\RoommateRequestStatus;
use App\Filament\Resources\RoommateRequestResource\Pages;
use App\Models\RoommateRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoommateRequestResource extends Resource
{
    protected static ?string $model = RoommateRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sender_id')
                    ->relationship('sender', 'full_name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('recipient_id')
                    ->relationship('recipient', 'full_name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(self::getStatusOptions())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient.full_name')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(static function ($state): string {
                        if ($state instanceof RoommateRequestStatus) {
                            return self::getStatusOptions()[$state->value] ?? $state->name;
                        }

                        return self::getStatusOptions()[$state] ?? (string) $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(self::getStatusOptions()),
            ])
            ->actions([
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
            'index' => Pages\ListRoommateRequests::route('/'),
            'create' => Pages\CreateRoommateRequest::route('/create'),
            'edit' => Pages\EditRoommateRequest::route('/{record}/edit'),
        ];
    }

    protected static function getStatusOptions(): array
    {
        return [
            RoommateRequestStatus::PENDING->value => 'Pending',
            RoommateRequestStatus::ACCEPTED->value => 'Accepted',
            RoommateRequestStatus::DENIED->value => 'Denied',
            RoommateRequestStatus::DELETED->value => 'Deleted',
        ];
    }
}
