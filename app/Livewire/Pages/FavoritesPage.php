<?php

namespace App\Livewire\Pages;

use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Livewire\Traits\WithFavoriting;
use App\Livewire\Traits\WithRequesting;
use App\Livewire\Traits\WithBlocking;
use App\Livewire\Traits\WithOnboardingSteps;
use App\Livewire\Traits\WithUserActionModals;
use App\Livewire\Traits\CanRetrieveUser;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables;
use App\Livewire\Traits;
use App\Models\ChatRoom;
use App\Models\Report;
use App\Models\RoommateRequest;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class FavoritesPage extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
    use
        InteractsWithForms,
        WithUserActionModals,
        WithFavoriting,
        WithRequesting,
        WithBlocking,
        WithOnboardingSteps,
        CanRetrieveUser,
        InteractsWithTable {
        applySortingToTableQuery as parentApplySortingToTableQuery;
    }

    public Collection $similarity_scores;
    /** @var array<int, int> */
    public array $restrictedFavoriteIds = [];

    protected function getListeners()
    {
        return [
            'refresh:component' => '$refresh',
            'actionTakenOnUser' => '$refresh'
        ];
    }

    public function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->getAuthModel()->favorites()->getQuery();
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage() == -1 ? $query->count() : $this->getTableRecordsPerPage());
    }

    protected function applySortingToTableQuery(Builder $query): Builder
    {
        $authUser = $this->getAuthModel();

        if (blank($authUser)) {
            $this->similarity_scores = collect();
            $this->restrictedFavoriteIds = [];

            return $query->whereRaw('1 = 0');
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, User> $records */
        $records = $query->withOnly([
            'course:id,name',
            'towns:id,name',
            'hobbies:id,name',
            'dislikes:id,name',
        ])->get();

        $recordIds = $records->pluck('id')->values();

        $eligibleFavoriteIds = User::query()
            ->whereIn('id', $recordIds)
            ->validSimilarityCandidates($authUser)
            ->pluck('id')
            ->all();

        $eligibleFavoriteIds = array_map('intval', $eligibleFavoriteIds);
        $eligibleFavoriteIdLookup = array_flip($eligibleFavoriteIds);

        $this->restrictedFavoriteIds = $recordIds
            ->reject(fn ($id): bool => array_key_exists((int) $id, $eligibleFavoriteIdLookup))
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();

        $scoredEligibleRecords = $authUser->calculateUsersSimilarityScore(
            $records
                ->filter(fn (User $record): bool => array_key_exists((int) $record->id, $eligibleFavoriteIdLookup))
                ->values()
        );

        $this->similarity_scores = $scoredEligibleRecords->mapWithKeys(fn ($model) => [$model->id => $model->similarity_score]);

        $res = $records;

        if ($this->getTableSortColumn() == "similarity_score" || $this->getTableSortColumn() == null) {
            $res = $res->sortBy(function (User $record): float {
                if ($this->isRestrictedFavorite($record)) {
                    return 1000000.0;
                }

                return (float) $this->similarity_scores->get($record->id, 0.0);
            });
            $this->tableSort = 'similarity_score';

            return $res->isEmpty() ? $query : $res->toQuery();
        }

        return $this->parentApplySortingToTableQuery($res->isEmpty() ? $query : $res->toQuery());
    }

    protected function getTableColumns(): array
    {
        return [
            Split::make([
                View::make('livewire.components.filament.tables.user-card-detail-row')
                    ->components([
                        ImageColumn::make('avatar_path')
                            ->circular()
                            ->grow(false)
                            ->extraAttributes(['class' => 'pl-0 pt-1']),

                        TextColumn::make('full_name')
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query
                                    ->orderBy('last_name', $direction)
                                    ->orderBy('first_name', $direction);
                            }),

                        TextColumn::make('course.name'),
                        TextColumn::make('towns.name'),
                        TextColumn::make('min_budget')
                            ->formatStateUsing(fn ($state) => number_format($state))
                            ->prefix('₦')
                            ->sortable(),

                        TextColumn::make('max_budget')
                            ->formatStateUsing(fn ($state) => number_format($state))
                            ->prefix('₦')
                            ->sortable(),

                        TextColumn::make('similarity_score')
                            ->getStateUsing(fn (User $record): string => $this->isRestrictedFavorite($record)
                                ? 'Restricted'
                                : (($this->similarity_scores->get($record->id) ?? 'N/A') . '%'))
                            ->color('danger')
                            ->sortable(),

                        TextColumn::make('hard_filter_restricted')
                            ->getStateUsing(fn (User $record): string => $this->isRestrictedFavorite($record) ? '1' : '0')
                            ->hidden(),
                    ]),
            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ...$this->getRoommateRequestingActions(),
            ...$this->getFavoritingActions(),

            ActionGroup::make([
                ...$this->getReportingAction(),
                ...$this->getBlockingActions(),
            ])
                ->color('gray')
                ->icon('heroicon-o-ellipsis-vertical'),
        ];
    }

    public function getTableRecordsPerPage(): int
    {
        return 9;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [9, 18, 36];
    }

    protected function getTableContentGrid(): ?array
    {
        return [
            'md' => 2,
            'lg' => 3,
        ];
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return fn (User $record) => match (true) {
            $this->isRestrictedFavorite($record) => 'filament-user-card user-favorited favorite-restricted',
            $this->hasAcceptedRoommateRequest($record) => 'filament-user-card roommate-request-accepted',
            $this->hasPendingRoommateRequestFrom($record) => 'filament-user-card roommate-request-received',
            $this->hasPendingRoommateRequestTo($record) => 'filament-user-card rooomate-request-sent',
            $this->hasBeenBlocked($record) => 'filament-user-card user-blocked',
            default => 'filament-user-card user-favorited',
        };
    }

    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Users Found';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'You have not added any favorites';
    }

    /** dynamic properties */
    #[Computed]
    public function favorites(): Collection
    {
        return $this->getAuthModel()->favorites()->get(['favoritee_id']);
    }

    #[Computed]
    public function blockedUsers(): Collection
    {
        return DB::table('blocklists')->where(['blocker_id' => $this->getAuthModel()->id])->get('blockee_id');
    }

    #[Computed]
    public function roommateRequests(): Collection
    {
        return $this->getAuthModel()->getRoommateRequests();
    }

    /** checks */
    protected function hasBeenBlocked(User $user): bool
    {
        return $this->blockedUsers
            ->pluck('blockee_id')
            ->contains($user->id);
    }

    protected function hasPendingRoommateRequestFrom(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->sender->is($user) && $roommateRequest->isPending();
            });
    }

    protected function hasPendingRoommateRequestTo(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->recipient->is($user) && $roommateRequest->isPending();
            });
    }

    protected function hasSentOrReceivedRoommateRequest(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->id === RoommateRequest::getCompositeKey($this->getAuthModel(), $user);
            });
    }

    protected function hasNoSentOrReceivedRoommateRequest(User $user): bool
    {
        return !$this->hasSentOrReceivedRoommateRequest($user);
    }

    protected function hasPendingSentOrReceivedRoommateRequest(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->id === RoommateRequest::getCompositeKey($this->getAuthModel(), $user)
                    && $roommateRequest->isPending();
            });
    }

    protected function hasAcceptedRoommateRequest(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->id === RoommateRequest::getCompositeKey($this->getAuthModel(), $user)
                    && $roommateRequest->isAccepted();
            });
    }

    protected function isRestrictedFavorite(User $user): bool
    {
        return in_array((int) $user->id, $this->restrictedFavoriteIds, true);
    }

    // actions
    protected function getReportingAction()
    {
        return [
            Action::make('report')
                ->label('Report User')
                ->icon('heroicon-o-flag')
                ->color('warning')
                ->visible(fn (User $record): bool => !$this->isRestrictedFavorite($record))
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Report ' . $record->full_name)
                ->modalDescription('Select the relevant Issues to submit a Report.')
                ->modalSubmitActionLabel('Submit')
                ->modalWidth('sm')
                ->schema([
                    CheckboxList::make('report_ids')
                        ->label('Reports')
                        ->options(Report::pluck('description', 'id')->transform(fn ($val) => ucfirst($val)))
                        ->required()
                        ->exists('reports', 'id')
                        ->extraAttributes(['class' => 'space-y-2']),
                ])
                ->action(function (User $record, array $data) {
                    if ($this->getAuthModel()->reportUser($record, $data['report_ids'])) {
                        Notification::make()
                            ->title("Report submitted succesfully")
                            ->body("Your report has been submitted. Our team will review your report ASAP. Thanks!")
                            ->success()
                            ->seconds(15)
                            ->send();
                    }
                }),
        ];
    }

    protected function getBlockingActions()
    {
        return [
            Action::make('block')
                ->label('Block User')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->visible(fn (User $record): bool => !$this->isRestrictedFavorite($record) && !$this->hasBeenBlocked($record))
                ->action(function (User $record) {
                    $this->blockUser($record);
                    $this->dispatchSelf('refresh:component');
                })
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Block ' . $record->full_name)
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will prevent <span class='font-semibold text-secondary-600'>{$record->full_name}</span> from viewing your profile and sending you Roommate requests.</p>")->toHtmlString())
                ->extraAttributes(['class' => 'mt-1']),
        ];
    }

    protected function getFavoritingActions()
    {
        return [
            Action::make('unfavorite')
                ->iconButton()
                ->label('Remove from Favorites')
                ->tooltip('Remove from Favorites')
                ->color('primary')
                ->icon('heroicon-s-star')
                ->action(function (User $record) {
                    $this->unfavorite($record);
                    $this->dispatchSelf('refresh:component');
                })
                ->requiresConfirmation()
                ->modalHeading('Remove From Favorites')
                ->modalHeading(fn (User $record) => "Remove {$record->full_name} from Favorites")
                ->extraAttributes([
                    'title' => 'Favorite button',
                    'class' => 'border border-secondary-300',
                    'style' => 'width: 3rem; border-radius: .5rem',
                    'id' => 'filament-tables-action-favorite'
                ])
        ];
    }

    protected function getRoommateRequestingActions()
    {
        return [
            Action::make('send-roommate-request')
                ->button()
                ->outlined()
                ->label('Send Request')
                ->icon('heroicon-s-user-plus')
                ->color('gray')
                ->extraAttributes([
                    'title' => 'send roommate request',
                    'class' => 'w-full filament-tables-action-send-roommate-request',
                ])
                ->action(function (User $record) {
                    $this->sendRoommateRequest($record);
                    $this->dispatchSelf('refresh:component');
                })
                ->requiresConfirmation()
                ->modalHeading('Send Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will send a Roommate roommate-request to <span class='font-semibold text-secondary-600'>{$record->full_name}</span>.</p>")->toHtmlString())
                ->visible(fn (User $record) => !$this->isRestrictedFavorite($record) && $this->hasNoSentOrReceivedRoommateRequest($record)),

            Action::make('accept-roommate-request')
                ->button()
                ->label('Accept Request')
                ->icon('heroicon-s-check-circle')
                ->color('primary')
                ->extraAttributes([
                    'title' => 'accept roommate request',
                    'class' => 'w-full filament-tables-action-accept-roommate-request',
                ])
                ->action(function (User $record) {
                    $this->acceptRoommateRequest($record);
                    $this->dispatchSelf('refresh:component');
                })
                ->requiresConfirmation()
                ->modalHeading('Accept Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will enable <span class='font-semibold text-secondary-600'>{$record->full_name}</span> to contact you via your configured Contact channels.</p>")->toHtmlString())
                ->visible(fn (User $record) => !$this->isRestrictedFavorite($record) && $this->hasPendingRoommateRequestFrom($record)),

            Action::make('delete-roommate-request')
                ->button()
                ->label('Delete Request')
                ->icon('heroicon-s-user-minus')
                ->color('danger')
                ->extraAttributes([
                    'title' => 'delete roommate request',
                    'class' => 'w-full filament-tables-action-delete-roommate-request',
                ])
                ->action(function (User $record) {
                    $this->deleteRoommateRequest($record);
                    $this->dispatchSelf('refresh:component');
                })
                ->requiresConfirmation()
                ->modalHeading('Delete Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will delete the Roommate request you sent to <span class='font-semibold text-secondary-600'>{$record->full_name}</span>.</p>")->toHtmlString())
                ->visible(fn (User $record) => !$this->isRestrictedFavorite($record) && $this->hasPendingRoommateRequestTo($record)),

            Action::make('contact-user')
                ->button()
                ->label('Contact User')
                ->icon('heroicon-s-phone-arrow-up-right')
                ->color('success')
                ->extraAttributes([
                    'title' => 'contact user',
                    'class' => 'w-full filament-tables-action-contact-user',
                ])
                ->modalHeading(fn (User $record): string => 'Contact ' . $record->full_name)
                ->modalSubmitAction(false)
                ->modalCancelAction(fn (Action $action) => $action->label('Close'))
                ->modalWidth('sm')
                ->modalContent(function (User $record) {
                    $authUser = $this->getAuthModel();
                    $chatRoom = ChatRoom::findBetween($authUser, $record);

                    if (!$chatRoom || !$chatRoom->hasBothSharedContacts()) {
                        return str('<p class="text-center text-secondary-600">Contact sharing is not yet mutual.</p>')->toHtmlString();
                    }

                    return view('livewire.partials.contact-user-modal-content', [
                        'channels' => $record->getVerifiedContactChannels(),
                    ]);
                })
                ->visible(fn (User $record) => !$this->isRestrictedFavorite($record) && $this->hasAcceptedRoommateRequest($record)),
        ];
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.favorites-page');

        return $view->layout('layouts.guest');
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        // Return null if not using translations
        return null;
    }
}
