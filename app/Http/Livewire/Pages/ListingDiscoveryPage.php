<?php

namespace App\Http\Livewire\Pages;

use App\Models\Listing;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListingDiscoveryPage extends Component implements Tables\Contracts\HasTable, HasForms
{
    use InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    public function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder
    {
        $user = $this->getAuthModel();

        if (blank($user)) {
            return Listing::query()->whereRaw('1 = 0');
        }

        return Listing::query()
            ->published()
            ->where('user_id', '<>', $user->getKey())
            ->whereHas(
                'user',
                fn (Builder $ownerQuery): Builder => $ownerQuery
                    ->where('verification_status', User::VERIFICATION_STATUS_APPROVED)
                    ->where('profile_updated', true)
                    ->whereNotNull('email_verified_at')
                    ->where('is_suspended', false)
            )
            ->when(
                filled($user->school_id),
                fn (Builder $query): Builder => $query->whereHas(
                    'user',
                    fn (Builder $ownerQuery): Builder => $ownerQuery->where('school_id', $user->school_id)
                )
            )
            ->forAdvancedPreferences($user)
            ->with('user')
            ->latest('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->searchable()
                ->weight('medium')
                ->limit(60),

            TextColumn::make('user.full_name')
                ->label('Host')
                ->formatStateUsing(fn (Listing $record): string => $record->user?->full_name ?? 'N/A')
                ->searchable(),

            TextColumn::make('city')
                ->searchable(),

            TextColumn::make('rent_amount')
                ->label('Rent')
                ->money('NGN')
                ->sortable(),

            TextColumn::make('rent_period')
                ->badge()
                ->formatStateUsing(fn (string $state): string => ucfirst($state)),

            TextColumn::make('move_in_date')
                ->date()
                ->sortable(),

            TextColumn::make('amenity_match')
                ->label('Amenities Match')
                ->badge()
                ->color('success')
                ->getStateUsing(fn (Listing $record): string => ($this->getAuthModel()?->calculateListingAmenitiesSimilarityScore($record) ?? 0) . '%'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view_profile')
                ->label('View Host')
                ->icon('heroicon-o-user')
                ->url(fn (Listing $record): string => route('profile.view', ['user' => $record->user])),
        ];
    }

    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Listing Matches Found';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'Try relaxing your listing filters in Account Settings or check back later.';
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.listing-discovery-page');

        return $view->layout('layouts.guest');
    }
}
