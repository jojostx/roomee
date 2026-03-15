<?php

namespace App\Filament\Resources\ListingResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListings extends ListRecords
{
    protected static string $resource = ListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

