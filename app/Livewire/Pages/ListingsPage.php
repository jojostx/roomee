<?php

namespace App\Livewire\Pages;

use App\Filament\Resources\ListingResource;
use App\Models\Listing;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ListingsPage extends Component implements Tables\Contracts\HasTable, HasForms
{
    use InteractsWithForms;
    use Tables\Concerns\InteractsWithTable;

    public function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder
    {
        $ownerId = $this->getAuthModel()?->getKey();

        if (blank($ownerId)) {
            return Listing::query()->whereRaw('1 = 0');
        }

        return Listing::query()
            ->where('user_id', $ownerId)
            ->latest('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->searchable()
                ->weight('medium')
                ->limit(45),

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

            IconColumn::make('is_published')
                ->label('Published')
                ->boolean()
                ->sortable(),

            TextColumn::make('updated_at')
                ->label('Updated')
                ->since(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create_listing')
                ->label('Create Listing')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->modalHeading('Create Listing')
                ->modalSubmitActionLabel('Save Listing')
                ->form($this->getListingFormSchema())
                ->action(function (array $data): void {
                    $owner = $this->getListingOwnerOrFail();

                    ListingResource::ensureOwnerCanCreateListing($owner);

                    $payload = $this->normalizeListingPayload($data);

                    ListingResource::ensureOwnerCanPublishListing(
                        $owner,
                        (bool) ($payload['is_published'] ?? false),
                    );

                    $owner->listings()->create($payload);

                    Notification::make()
                        ->title('Listing created')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit_listing')
                ->label('Edit')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Edit Listing')
                ->modalSubmitActionLabel('Update Listing')
                ->fillForm(fn (Listing $record): array => [
                    'title' => $record->title,
                    'description' => $record->description,
                    'address' => $record->address,
                    'city' => $record->city,
                    'rent_amount' => $record->rent_amount,
                    'rent_period' => $record->rent_period,
                    'move_in_date' => optional($record->move_in_date)->toDateString(),
                    'amenities' => $record->amenities ?? [],
                    'house_rules' => $record->house_rules ?? [],
                    'images' => $record->images ?? [],
                    'is_published' => (bool) $record->is_published,
                ])
                ->form($this->getListingFormSchema())
                ->action(function (Listing $record, array $data): void {
                    $owner = $this->getListingOwnerOrFail();
                    $this->ensureListingBelongsToOwner($record, $owner);

                    $payload = $this->normalizeListingPayload($data);

                    ListingResource::ensureOwnerCanPublishListing(
                        $owner,
                        (bool) ($payload['is_published'] ?? false),
                        $record,
                    );

                    $record->update($payload);

                    Notification::make()
                        ->title('Listing updated')
                        ->success()
                        ->send();
                }),

            Action::make('toggle_publish')
                ->label(fn (Listing $record): string => $record->is_published ? 'Unpublish' : 'Publish')
                ->icon(fn (Listing $record): string => $record->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-globe-alt')
                ->color(fn (Listing $record): string => $record->is_published ? 'gray' : 'success')
                ->requiresConfirmation()
                ->action(function (Listing $record): void {
                    $owner = $this->getListingOwnerOrFail();
                    $this->ensureListingBelongsToOwner($record, $owner);

                    $nextPublishedState = !$record->is_published;

                    ListingResource::ensureOwnerCanPublishListing(
                        $owner,
                        $nextPublishedState,
                        $record,
                    );

                    $record->update([
                        'is_published' => $nextPublishedState,
                    ]);

                    Notification::make()
                        ->title($nextPublishedState ? 'Listing published' : 'Listing unpublished')
                        ->success()
                        ->send();
                }),

            Tables\Actions\DeleteAction::make()
                ->before(function (Listing $record): void {
                    $owner = $this->getListingOwnerOrFail();
                    $this->ensureListingBelongsToOwner($record, $owner);
                }),
        ];
    }

    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Listings Yet';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'Create your first room listing to start receiving matches.';
    }

    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    protected function getListingFormSchema(): array
    {
        return [
            Section::make('Listing Details')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(120),

                    Textarea::make('description')
                        ->required()
                        ->rows(4)
                        ->maxLength(2000)
                        ->columnSpanFull(),

                    TextInput::make('address')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('city')
                        ->required()
                        ->maxLength(120),

                    TextInput::make('rent_amount')
                        ->label('Rent Amount')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->prefix('N'),

                    Select::make('rent_period')
                        ->required()
                        ->options([
                            Listing::RENT_PERIOD_MONTHLY => 'Monthly',
                            Listing::RENT_PERIOD_ANNUALLY => 'Annually',
                        ])
                        ->default(Listing::RENT_PERIOD_MONTHLY),

                    DatePicker::make('move_in_date')
                        ->required()
                        ->native(false),
                ])
                ->columns(2),

            Section::make('Home Vibe')
                ->schema([
                    CheckboxList::make('amenities')
                        ->label('Amenities')
                        ->options(Listing::AMENITY_OPTIONS)
                        ->columns(2),

                    CheckboxList::make('house_rules')
                        ->label('House Rules')
                        ->options(Listing::HOUSE_RULE_OPTIONS)
                        ->columns(2),
                ]),

            Section::make('Photos & Visibility')
                ->schema([
                    FileUpload::make('images')
                        ->label('Listing Images')
                        ->disk('public')
                        ->directory('listings')
                        ->visibility('public')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->columnSpanFull(),

                    Toggle::make('is_published')
                        ->label('Publish listing')
                        ->helperText('Only eligible verified users can publish.'),
                ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeListingPayload(array $data): array
    {
        return [
            'title' => (string) ($data['title'] ?? ''),
            'description' => (string) ($data['description'] ?? ''),
            'address' => (string) ($data['address'] ?? ''),
            'city' => (string) ($data['city'] ?? ''),
            'rent_amount' => (int) ($data['rent_amount'] ?? 0),
            'rent_period' => (string) ($data['rent_period'] ?? Listing::RENT_PERIOD_MONTHLY),
            'move_in_date' => $data['move_in_date'] ?? null,
            'amenities' => $this->normalizeStringList($data['amenities'] ?? []),
            'house_rules' => $this->normalizeStringList($data['house_rules'] ?? []),
            'images' => $this->normalizeStringList($data['images'] ?? []),
            'is_published' => (bool) ($data['is_published'] ?? false),
        ];
    }

    /**
     * @param  mixed  $value
     * @return array<int, string>
     */
    protected function normalizeStringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter(
            array_map(
                static fn (mixed $item): string => is_scalar($item) ? trim((string) $item) : '',
                $value
            ),
            static fn (string $item): bool => $item !== ''
        ));
    }

    protected function ensureListingBelongsToOwner(Listing $record, User $owner): void
    {
        if ((int) $record->user_id === (int) $owner->getKey()) {
            return;
        }

        abort(403);
    }

    protected function getListingOwnerOrFail(): User
    {
        $owner = $this->getAuthModel();

        if (blank($owner)) {
            throw ValidationException::withMessages([
                'listing' => 'You must be logged in to manage listings.',
            ]);
        }

        ListingResource::ensureOwnerCanDraftListing($owner);

        return $owner;
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.listings-page');

        return $view->layout('layouts.guest');
    }
}
