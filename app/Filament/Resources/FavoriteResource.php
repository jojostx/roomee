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
use App\Filament\Resources\FavoriteResource\Pages\ListFavorites;
use App\Filament\Resources\FavoriteResource\Pages\CreateFavorite;
use App\Filament\Resources\FavoriteResource\Pages\EditFavorite;
use App\Filament\Resources\FavoriteResource\Pages;
use App\Models\Favorite;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FavoriteResource extends Resource
{
    protected static ?string $model = Favorite::class;

    protected static ?string $navigationLabel = 'Favorites';

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-star';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Connections';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('favoriter_id')
                    ->relationship('favoriter', 'full_name')
                    ->searchable()
                    ->required(),
                Select::make('favoritee_id')
                    ->relationship('favoritee', 'full_name')
                    ->searchable()
                    ->required()
                    ->different('favoriter_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('favoriter.full_name')
                    ->label('Favoriter')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('favoritee.full_name')
                    ->label('Favorited User')
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
            'index' => ListFavorites::route('/'),
            'create' => CreateFavorite::route('/create'),
            'edit' => EditFavorite::route('/{record}/edit'),
        ];
    }
}
