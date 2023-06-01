<?php

namespace App\Http\Livewire\Pages;

use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Report;
use Livewire\Component;
use App\Http\Livewire\Traits;
use App\Models\RoommateRequest;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

class DashboardPage extends Component implements Tables\Contracts\HasTable
{
    use
        Traits\WithFavoriting,
        Traits\WithRequesting,
        Traits\WithBlocking,
        Traits\CanRetrieveUser,
        Tables\Concerns\InteractsWithTable {
        applySortingToTableQuery as parentApplySortingToTableQuery;
    }

    public Collection $similarity_scores;

    protected function getListeners()
    {
        return [
            'refresh-component' => '$refresh',
        ];
    }

    public function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder
    {
        return $this->getAuthModel()
            ->validNonBlockingUsers();
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

        if ($this->tableSortColumn == "similarity_score" || $this->tableSortColumn == null) {
            $res = $res->sortBy('similarity_score');
            $this->tableSortColumn = 'similarity_score';

            return $res->isEmpty() ? $query : $res->toQuery();
        }

        return $this->parentApplySortingToTableQuery($res->isEmpty() ? $query : $res->toQuery());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\Layout\Split::make([
                Tables\Columns\Layout\View::make('livewire.components.filament.tables.user-card-detail-row')
                    ->components([
                        Tables\Columns\ImageColumn::make('avatar')
                            ->disk('avatars')
                            ->rounded()
                            ->grow(false)
                            ->extraAttributes(['class' => 'pl-0 pt-1']),

                        Tables\Columns\TextColumn::make('full_name')
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query
                                    ->orderBy('last_name', $direction)
                                    ->orderBy('first_name', $direction);
                            }),

                        Tables\Columns\TextColumn::make('course.name'),
                        Tables\Columns\TextColumn::make('towns.name'),
                        Tables\Columns\TextColumn::make('min_budget')
                            ->formatStateUsing(fn ($state) => number_format($state))
                            ->prefix('₦')
                            ->sortable(),

                        Tables\Columns\TextColumn::make('max_budget')
                            ->formatStateUsing(fn ($state) => number_format($state))
                            ->prefix('₦')
                            ->sortable(),

                        Tables\Columns\TextColumn::make('similarity_score')
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
            
            Tables\Actions\ActionGroup::make([
                ...$this->getFavoritingActions(),
                ...$this->getReportingAction(),
                ...$this->getBlockingActions(),
            ])
                ->color('gray')
                ->icon('heroicon-o-dots-vertical'),
        ];
    }

    protected function getTableRecordsPerPage(): int
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
        return 'No Users Found';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'No User currently match your preferences, Please Check back later.';
    }

    /** dynamic properties */
    public function getFavoritesProperty(): Collection
    {
        return $this->getAuthModel()->favorites()->get(['favoritee_id']);
    }

    public function getBlockedUsersProperty(): Collection
    {
        return DB::table('blocklists')->where(['blocker_id' => $this->getAuthModel()->id])->get('blockee_id');
    }

    public function getRoommateRequestsProperty(): Collection
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

    protected function hasDeniedRoommateRequestFrom(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->sender->is($user) && $roommateRequest->isDenied();
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
            Tables\Actions\Action::make('report')
                ->label('Report User')
                ->icon('heroicon-o-flag')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Report ' . $record->full_name)
                ->modalSubheading('Select the relevant Issues to submit a Report.')
                ->modalButton('Submit')
                ->modalWidth('sm')
                ->form([
                    Forms\Components\CheckboxList::make('report_ids')
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
            Tables\Actions\Action::make('block')
                ->label('Block User')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->action(function (User $record) {
                    $this->blockUser($record);
                    $this->emitSelf('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Block ' . $record->full_name)
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will prevent <span class='font-semibold text-secondary-600'>{$record->full_name}</span> from viewing your profile and sending you Roommate requests.</p>")->toHtmlString())
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => !$this->hasBeenBlocked($record)),

            Tables\Actions\Action::make('unblock')
                ->label('Unblock User')
                ->icon('heroicon-o-lock-open')
                ->color('danger')
                ->action(function (User $record) {
                    $this->unblockUser($record);
                    $this->emitSelf('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Unblock ' . $record->full_name)
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will allow <span class='font-semibold text-secondary-600'>{$record->full_name}</span> to view your profile and send you Roommate requests.</p>")->toHtmlString())
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => $this->hasBeenBlocked($record))
        ];
    }

    protected function getFavoritingActions()
    {
        return [
            Tables\Actions\Action::make('favorite')
                ->label('Add to Favorites')
                ->tooltip('Add to Favorites')
                ->color('primary')
                ->icon('heroicon-o-star')
                ->action(function (User $record) {
                    $this->favorite($record);
                    $this->emitSelf('refresh-component');
                })
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => !$this->hasBeenFavorited($record)),

            Tables\Actions\Action::make('unfavorite')
                ->label('Remove from Favorites')
                ->tooltip('Remove from Favorites')
                ->color('primary')
                ->icon('heroicon-s-star')
                ->action(function (User $record) {
                    $this->unfavorite($record);
                    $this->emitSelf('refresh-component');
                })
                ->extraAttributes(['class' => 'mt-1'])
                ->visible(fn (User $record): bool => $this->hasBeenFavorited($record))
        ];
    }

    protected function getRoommateRequestingActions()
    {
        return [
            Tables\Actions\Action::make('send-roommate-request')
                ->button()
                ->outlined()
                ->label('Send Request')
                ->icon('heroicon-s-user-add')
                ->color('secondary')
                ->extraAttributes([
                    'title' => 'send roommate request',
                    'class' => 'w-full filament-tables-action-send-roommate-request',
                ])
                ->action(function (User $record) {
                    $this->sendRoommateRequest($record);
                    $this->emitSelf('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading('Send Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will send a Roommate request to <span class='font-semibold text-secondary-600'>{$record->full_name}</span>.</p>")->toHtmlString())
                ->visible(fn (User $record) => $this->hasNoSentOrReceivedRoommateRequest($record)),

            Tables\Actions\Action::make('accept-roommate-request')
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
                    $this->emitSelf('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading('Accept Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will enable <span class='font-semibold text-secondary-600'>{$record->full_name}</span> to contact you via your configured Contact channels.</p>")->toHtmlString())
                ->visible(fn (User $record) => $this->hasPendingRoommateRequestFrom($record)),

            Tables\Actions\Action::make('delete-roommate-request')
                ->button()
                ->label('Delete Request')
                ->icon('heroicon-s-user-remove')
                ->color('danger')
                ->extraAttributes([
                    'title' => 'delete roommate request',
                    'class' => 'w-full filament-tables-action-delete-roommate-request',
                ])
                ->action(function (User $record) {
                    $this->deleteRoommateRequest($record);
                    $this->emitSelf('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading('Delete Roommate Request')
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will delete the Roommate request you sent to <span class='font-semibold text-secondary-600'>{$record->full_name}</span>.</p>")->toHtmlString())
                ->visible(fn (User $record) => $this->hasPendingRoommateRequestTo($record)),

            Tables\Actions\Action::make('contact-user')
                ->button()
                ->label('Contact User')
                ->icon('heroicon-s-phone-outgoing')
                ->color('success')
                ->extraAttributes([
                    'title' => 'contact user',
                    'class' => 'w-full filament-tables-action-contact-user',
                ])
                ->action(function (User $record) {
                    $this->emit('openModal', 'components.modals.contact-user-modal', ["user" => $record->uuid]);
                    $this->emitSelf('refresh-component');
                })
                ->visible(fn (User $record) => $this->hasAcceptedRoommateRequest($record)),
        ];
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.dashboard-page');

        return $view->layout('layouts.guest');
    }
}
