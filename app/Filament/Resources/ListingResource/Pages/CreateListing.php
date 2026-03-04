<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateListing extends CreateRecord
{
    protected static string $resource = ListingResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $owner = User::query()->find($data['user_id'] ?? null);

        if (blank($owner)) {
            throw ValidationException::withMessages([
                'user_id' => 'Please select a valid listing owner.',
            ]);
        }

        ListingResource::ensureOwnerCanCreateListing($owner);
        ListingResource::ensureOwnerCanPublishListing(
            $owner,
            (bool) ($data['is_published'] ?? false),
            null,
        );

        return $data;
    }
}

