<?php

namespace App\Filament\Resources;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RoommateRequestResource\Pages\ListRoommateRequests;
use App\Filament\Resources\RoommateRequestResource\Pages\CreateRoommateRequest;
use App\Filament\Resources\RoommateRequestResource\Pages\EditRoommateRequest;
use App\Enums\RoommateRequestStatus;
use App\Filament\Resources\RoommateRequestResource\Pages;
use App\Models\RoommateRequest;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoommateRequestResource extends Resource
{
    protected static ?string $model = RoommateRequest::class;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Requests';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sender_id')
                    ->relationship('sender', 'full_name')
                    ->searchable()
                    ->required(),
                Select::make('recipient_id')
                    ->relationship('recipient', 'full_name')
                    ->searchable()
                    ->different('sender_id')
                    ->required(),
                Select::make('status')
                    ->options(self::getStatusOptions())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender.full_name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipient.full_name')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(static function ($state): string {
                        if ($state instanceof RoommateRequestStatus) {
                            return self::getStatusOptions()[$state->value] ?? $state->name;
                        }

                        return self::getStatusOptions()[$state] ?? (string) $state;
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::getStatusOptions()),
            ])
            ->recordActions([
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
            'index' => ListRoommateRequests::route('/'),
            'create' => CreateRoommateRequest::route('/create'),
            'edit' => EditRoommateRequest::route('/{record}/edit'),
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
