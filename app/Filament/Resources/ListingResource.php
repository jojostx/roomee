<?php

namespace App\Filament\Resources;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ListingResource\Pages\ListListings;
use App\Filament\Resources\ListingResource\Pages\CreateListing;
use App\Filament\Resources\ListingResource\Pages\EditListing;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Filament\Resources\ListingResource\Pages;
use App\Models\Listing;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static ?string $navigationLabel = 'Room Listings';

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-home-modern';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return 'Listings';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Listing Basics')
                    ->description('Share a friendly and clear snapshot of the space you are offering.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Listing Owner')
                            ->relationship(
                                'user',
                                'first_name',
                                modifyQueryUsing: fn (Builder $query): Builder => $query
                                    ->where('role', UserRole::USER->value)
                                    ->whereNotNull('email_verified_at')
                                    ->where('profile_updated', true)
                                    ->where('verification_status', VerificationStatus::APPROVED)
                                    ->where('is_suspended', false)
                            )
                            ->getOptionLabelFromRecordUsing(fn (User $record): string => $record->full_name)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->default(fn (): ?int => auth()->id()),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(120)
                            ->placeholder('Bright 2-bedroom flat near campus with calm house vibe'),

                        Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->maxLength(2000)
                            ->placeholder('Describe the room, shared spaces, and what kind of roommate energy fits best.')
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
                            ->native(false)
                            ->minDate(now()->toDateString()),
                    ])
                    ->columns(2),

                Section::make('Home Vibe')
                    ->description('Set clear expectations to attract compatible roommates.')
                    ->schema([
                        CheckboxList::make('amenities')
                            ->label('Amenities')
                            ->options(Listing::AMENITY_OPTIONS)
                            ->columns(2)
                            ->helperText('Select all amenities currently available.'),

                        CheckboxList::make('house_rules')
                            ->label('House Rules')
                            ->options(Listing::HOUSE_RULE_OPTIONS)
                            ->columns(2)
                            ->helperText('Choose the boundaries that keep the home peaceful.'),
                    ]),

                Section::make('Photos & Visibility')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Listing Images')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('listings')
                            ->helperText('Upload clear photos of the room and shared spaces.')
                            ->columnSpanFull(),

                        Toggle::make('is_published')
                            ->label('Publish Listing')
                            ->live()
                            ->disabled(
                                fn (Get $get): bool => !static::canPublishForOwner((int) ($get('user_id') ?? 0))
                                    && !((bool) ($get('is_published') ?? false))
                            )
                            ->helperText(fn (Get $get): string => static::publishVisibilityHelperText((int) ($get('user_id') ?? 0))),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.first_name')
                    ->label('Owner')
                    ->formatStateUsing(fn (Listing $record): string => $record->user?->full_name ?? 'N/A')
                    ->sortable(),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(45),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

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
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published'),

                SelectFilter::make('rent_period')
                    ->options([
                        Listing::RENT_PERIOD_MONTHLY => 'Monthly',
                        Listing::RENT_PERIOD_ANNUALLY => 'Annually',
                    ]),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    /**
     * @return array<string, string>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListListings::route('/'),
            'create' => CreateListing::route('/create'),
            'edit' => EditListing::route('/{record}/edit'),
        ];
    }

    public static function canPublishForOwner(int $ownerId): bool
    {
        if ($ownerId <= 0) {
            return false;
        }

        $owner = User::query()->find($ownerId);

        return filled($owner) && $owner->canManageListings();
    }

    public static function publishVisibilityHelperText(int $ownerId): string
    {
        if ($ownerId <= 0) {
            return 'Choose a listing owner first.';
        }

        $owner = User::query()->find($ownerId);

        if (blank($owner)) {
            return 'Choose a valid listing owner first.';
        }

        if (!$owner->canManageListings()) {
            return 'Owner must be a verified user with completed profile, verified email, and approved identity verification.';
        }

        return 'This listing is visible to seekers when published.';
    }

    public static function ensureOwnerCanDraftListing(User $owner): void
    {
        if ($owner->canManageListings()) {
            return;
        }

        throw ValidationException::withMessages([
            'user_id' => 'Only authenticated users with verified email, completed profile, and approved identity verification can manage listings.',
        ]);
    }

    public static function ensureOwnerCanCreateListing(User $owner): void
    {
        static::ensureOwnerCanDraftListing($owner);

        if (
            !$owner->is_premium
            && $owner->listings()->where('is_published', true)->exists()
        ) {
            throw ValidationException::withMessages([
                'user_id' => 'This user already has one active listing. Upgrade to premium or unpublish the current active listing first.',
            ]);
        }
    }

    public static function ensureOwnerCanPublishListing(User $owner, bool $isPublished, ?Listing $ignoreListing = null): void
    {
        if (!$isPublished) {
            return;
        }

        static::ensureOwnerCanDraftListing($owner);

        if ($owner->is_premium) {
            return;
        }

        $activeListingQuery = $owner
            ->listings()
            ->where('is_published', true);

        if (filled($ignoreListing)) {
            $activeListingQuery->whereKeyNot($ignoreListing->getKey());
        }

        if ($activeListingQuery->exists()) {
            throw ValidationException::withMessages([
                'is_published' => 'Non-premium users can only keep one active listing at a time.',
            ]);
        }
    }
}
