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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use App\Livewire\Traits\CanReactToRoommateRequestUpdate;
use App\Models\RoommateRequest;
use Illuminate\Support\Collection;
use Livewire\Component;
use Filament\Tables;
use App\Livewire\Traits;
use App\Models\Report;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;


class RoommateRequestsPage extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
    use CanReactToRoommateRequestUpdate;
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

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            'refresh-component' => '$refresh',
            'actionTakenOnUser' => '$refresh',
            'resetUsers' => 'resetUsersWhenSentRoommateRequestIsDeleted',
            "echo-private:roommate-request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }

    // fires a card component refresh when another user blocks the currently authenticated user
    protected function handleUserblockedEvent($data)
    {
        $this->dispatch('refreshChildren:' . $data['blocker_id']);
        $this->dispatch('resetUsers', $data['blocker_id']);
    }

    public function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->getAuthModel()->allPotentialRoommates()->getQuery();
    }

    protected function getTableFilters(): array
    {
        return [
            TernaryFilter::make('requests')
                ->placeholder('All')
                ->trueLabel('Received')
                ->falseLabel('Sent')
                ->queries(
                    true: fn (Builder $query) => $query->where('recipient_id', $this->getAuthModel()->getKey()),
                    false: fn (Builder $query) => $query->whereNot('recipient_id', $this->getAuthModel()->getKey()),
                    blank: fn (Builder $query) => $query,
                )
        ];
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage() == -1 ? $query->count() : $this->getTableRecordsPerPage());
    }

    protected function applySortingToTableQuery(Builder $query): Builder
    {
        /** @var \Illuminate\Database\Eloquent\Collection */
        $res = $this
            ->getAuthModel()
            ->calculateUsersSimilarityScore(
                $query->withOnly([
                    'course:id,name',
                    'towns:id,name',
                    'hobbies:id,name',
                    'dislikes:id,name'
                ])->get()
            );

        $this->similarity_scores = $res->mapWithKeys(fn ($model) => [$model->id => $model->similarity_score]);

        if ($this->getTableSortColumn() == "similarity_score" || $this->getTableSortColumn() == null) {
            $res = $res->sortBy('similarity_score');
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
                        TextColumn::make('pivot_created_at')
                            ->formatStateUsing(fn ($state) => filled($state) ? Date::parse($state)->setTimezone('WAT') : null)
                            ->sortable(),

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
                            ->getStateUsing(fn (User $record): string => $this->similarity_scores->get($record->id) . '%')
                            ->color('danger')
                            ->sortable(),
                    ]),
            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ...$this->getRoommateRequestingActions(),

            ActionGroup::make([
                ...$this->getFavoritingActions(),
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
            $this->hasAcceptedRoommateRequest($record) => 'filament-user-card roommate-request-accepted',
            $this->hasPendingRoommateRequestFrom($record) => 'filament-user-card roommate-request-received',
            $this->hasPendingRoommateRequestTo($record) => 'filament-user-card rooomate-request-sent',
            $this->hasBeenBlocked($record) => 'filament-user-card user-blocked',
            $this->hasBeenFavorited($record) => 'filament-user-card user-favorited',
            default => 'filament-user-card',
        };
    }

    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Roommate Requests Found';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'You have not sent or received any Roommate Requests.';
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

    protected function hasBeenFavorited(User $user): bool
    {
        return $this->favorites
            ->pluck('favoritee_id')
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

    // actions
    protected function getReportingAction()
    {
        return [
            Action::make('report')
                ->label('Report User')
                ->icon('heroicon-o-flag')
                ->color('warning')
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
                ->action(function (User $record) {
                    $this->blockUser($record);
                    $this->dispatch('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Block ' . $record->full_name)
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will prevent <span class='font-semibold text-secondary-600'>{$record->full_name}</span> from viewing your profile and sending you Roommate requests.</p>")->toHtmlString())
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => !$this->hasBeenBlocked($record)),
        ];
    }

    protected function getFavoritingActions()
    {
        return [
            Action::make('favorite')
                ->label('Add to Favorites')
                ->tooltip('Add to Favorites')
                ->color('primary')
                ->icon('heroicon-o-star')
                ->action(function (User $record) {
                    $this->favorite($record);
                    $this->dispatch('refresh-component');
                })
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => !$this->hasBeenFavorited($record)),

            Action::make('unfavorite')
                ->label('Remove from Favorites')
                ->tooltip('Remove from Favorites')
                ->color('primary')
                ->icon('heroicon-s-star')
                ->action(function (User $record) {
                    $this->unfavorite($record);
                    $this->dispatch('refresh-component');
                })
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => $this->hasBeenFavorited($record))
        ];
    }

    protected function getRoommateRequestingActions()
    {
        return [
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
                    $this->dispatch('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading('Accept Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will accept <span class='font-semibold text-secondary-600'>{$record->full_name}</span>'s roommate request, open chat, and keep contact details hidden until both of you choose to share them.</p>")->toHtmlString())
                ->visible(fn (User $record) => $this->hasPendingRoommateRequestFrom($record)),

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
                    $this->dispatch('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading('Delete Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will delete the Roommate request you sent to <span class='font-semibold text-secondary-600'>{$record->full_name}</span>.</p>")->toHtmlString())
                ->visible(fn (User $record) => $this->hasPendingRoommateRequestTo($record)),

            Action::make('chat-user')
                ->button()
                ->label('Chat')
                ->icon('heroicon-s-chat-bubble-left-right')
                ->color('success')
                ->extraAttributes([
                    'title' => 'open chat',
                    'class' => 'w-full filament-tables-action-chat-user',
                ])
                ->action(function (User $record) {
                    $this->openChat($record);
                })
                ->visible(fn (User $record) => $this->hasAcceptedRoommateRequest($record)),

            Action::make('unmatch-roommate')
                ->button()
                ->label('Unmatch')
                ->icon('heroicon-o-user-minus')
                ->color('danger')
                ->extraAttributes([
                    'title' => 'unmatch roommate',
                    'class' => 'w-full filament-tables-action-unmatch-roommate',
                ])
                ->action(function (User $record) {
                    $this->unmatchRoommate($record);
                    $this->dispatch('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Unmatch ' . $record->full_name)
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will remove your roommate match with <span class='font-semibold text-secondary-600'>{$record->full_name}</span>. Existing chat history will stay available, but contact sharing will be reset.</p>")->toHtmlString())
                ->visible(fn (User $record) => $this->hasAcceptedRoommateRequest($record)),
        ];
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.roommate-requests-page');

        return $view->layout('layouts.guest');
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        // Return null if not using translations
        return null;
    }
}
