<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlocklistResource\Pages;
use App\Models\Blocklist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlocklistResource extends Resource
{
    protected static ?string $model = Blocklist::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $navigationGroup = 'Connections';

    protected static ?string $navigationLabel = 'Blocklist';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('blocker_id')
                    ->relationship('blocker', 'full_name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('blockee_id')
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
                Tables\Columns\TextColumn::make('blocker.full_name')
                    ->label('Blocker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('blockee.full_name')
                    ->label('Blocked User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListBlocklists::route('/'),
            'create' => Pages\CreateBlocklist::route('/create'),
            'edit' => Pages\EditBlocklist::route('/{record}/edit'),
        ];
    }
}
