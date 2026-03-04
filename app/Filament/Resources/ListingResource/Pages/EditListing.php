<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use App\Models\Listing;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditListing extends EditRecord
{
    protected static string $resource = ListingResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $ownerId = $data['user_id'] ?? $this->record->user_id;
        $owner = User::query()->find($ownerId);

        if (blank($owner)) {
            throw ValidationException::withMessages([
                'user_id' => 'Please select a valid listing owner.',
            ]);
        }

        /** @var Listing $listing */
        $listing = $this->record;

        ListingResource::ensureOwnerCanDraftListing($owner);
        ListingResource::ensureOwnerCanPublishListing(
            $owner,
            (bool) ($data['is_published'] ?? false),
            $listing,
        );

        return $data;
    }
}
