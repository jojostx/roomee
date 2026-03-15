<?php

namespace App\Filament\Resources;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\BlocklistResource\Pages\ListBlocklists;
use App\Filament\Resources\BlocklistResource\Pages\CreateBlocklist;
use App\Filament\Resources\BlocklistResource\Pages\EditBlocklist;
use App\Filament\Resources\BlocklistResource\Pages;
use App\Models\Blocklist;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlocklistResource extends Resource
{
    protected static ?string $model = Blocklist::class;

    protected static ?string $navigationLabel = 'Blocklist';

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-lock-closed';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Connections';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('blocker_id')
                    ->relationship('blocker', 'full_name')
                    ->searchable()
                    ->required(),
                Select::make('blockee_id')
                    ->relationship('blockee', 'full_name')
                    ->searchable()
                    ->required()
                    ->different('blocker_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('blocker.full_name')
                    ->label('Blocker')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('blockee.full_name')
                    ->label('Blocked User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListBlocklists::route('/'),
            'create' => CreateBlocklist::route('/create'),
            'edit' => EditBlocklist::route('/{record}/edit'),
        ];
    }
}
