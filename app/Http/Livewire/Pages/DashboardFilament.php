<?php

namespace App\Http\Livewire\Pages;

use Closure;
use App\Models\Report;
use App\Models\RoommateRequest;
use App\Models\User;
use Livewire\Component;
use Filament\Tables;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardFilament extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable {
        applySortingToTableQuery as parentApplySortingToTableQuery;
    }

    protected Collection $similarity_scores;

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder
    {
        $user = $this->getAuthModel();

        return User::query()
            ->excludeUser($user->id)
            ->whereIntegerNotInRaw('id', $this->blockedUsers->pluck('blockee_id'))
            ->whereIntegerNotInRaw('id', $this->blockers->pluck('blocker_id'))
            ->gender($user->gender)
            ->school($user->school_id);
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
                $query->with([
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
                            ->grow(false)->extraAttributes(['class' => 'pl-0 pt-1']),

                        Tables\Columns\TextColumn::make('full_name')
                            ->sortable(query: function (Builder $query, string $direction): Builder {
                                return $query
                                    ->orderBy('last_name', $direction)
                                    ->orderBy('first_name', $direction);
                            })
                            ->extraAttributes(['class' => 'overflow-x-hidden text-base font-semibold text-secondary-700 text-ellipsis']),

                        Tables\Columns\TextColumn::make('course.name')
                            ->extraAttributes(['class' => 'overflow-x-hidden text-sm text-ellipsis text-secondary-500']),

                        Tables\Columns\TextColumn::make('towns.name')
                            ->extraAttributes(['class' => 'overflow-x-hidden text-sm text-ellipsis text-secondary-500']),

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
            Tables\Actions\Action::make('request')
                ->button()
                ->outlined(fn (User $record): bool => $this->hasNotSentOrRecievedRoommateRequest($record))
                ->label(function (User $record): string {
                    return match (true) {
                        $this->hasNotSentOrRecievedRoommateRequest($record) => 'Send Request',
                        $this->hasRoommateRequestFrom($record) => 'Accept Request',
                        $this->hasSentRoommateRequestTo($record) => 'Delete Request',
                        default => 'Contact ' . ucfirst($record->full_name)
                    };
                })
                ->icon(function (User $record): string {
                    if ($this->hasNotSentOrRecievedRoommateRequest($record)) {
                        return 'heroicon-s-user-add'; //+
                    } elseif ($this->hasRoommateRequestFrom($record)) { //@duplicate query #2
                        return 'heroicon-s-check-circle'; //V
                    } elseif ($this->hasSentRoommateRequestTo($record)) { //@duplicate query #3
                        return 'heroicon-s-user-remove'; //-
                    } else {
                        return 'heroicon-s-phone-outgoing'; //c
                    }
                })
                ->color(fn (User $record): string => $this->hasNotSentOrRecievedRoommateRequest($record) ? 'secondary' : 'primary')
                ->extraAttributes([
                    'title' => 'send request',
                    'class' => 'w-full',
                    'id' => 'filament-tables-action-request'
                ]),

            Tables\Actions\Action::make('favorite')
                ->iconButton()
                ->label(fn (User $record): string => $this->hasBeenFavorited($record) ? 'Unfavorite' : 'Favorite')
                ->tooltip(fn (User $record): string => $this->hasBeenFavorited($record) ? 'Remove from Favorites' : 'Add to Favorites')
                ->color(fn (User $record): string => $this->hasBeenFavorited($record) ? 'primary' : 'secondary')
                ->icon(fn (User $record): string => $this->hasBeenFavorited($record) ? 'heroicon-s-star' : 'heroicon-o-star')
                ->extraAttributes([
                    'title' => 'Favorite button',
                    'class' => 'border border-secondary-300',
                    'style' => 'width: 3rem; border-radius: .5rem',
                    'id' => 'filament-tables-action-favorite'
                ]),

            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('report')
                    ->icon('heroicon-o-flag')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record) => 'Report ' . $record->full_name)
                    ->modalSubheading('Select the relevant issues to submit a Report.')
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

                Tables\Actions\Action::make('block')
                    ->icon('heroicon-o-ban')
                    ->color('danger')
                    ->extraAttributes(['class' => 'mt-1']),
            ])
                ->color('gray')
                ->icon('heroicon-o-dots-vertical'),
        ];
    }

    protected function getTableRecordsPerPage(): int
    {
        return 10;
    }

    protected function getTableContentGrid(): ?array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return fn () => 'filament-user-card';
    }

    /** dynamic properties */
    public function getBlockedUsersProperty(): Collection
    {
        return DB::table('blocklists')->where(['blocker_id' => $this->getAuthModel()->id])->get('blockee_id');
    }

    public function getBlockersProperty(): Collection
    {
        return DB::table('blocklists')->where(['blockee_id' => $this->getAuthModel()->id])->get('blocker_id');
    }

    public function getFavoritesProperty(): Collection
    {
        // returns a collection with stdClasses with key {'favoritee_id' => $value}
        return DB::table('favorites')->where(['favoriter_id' => $this->getAuthModel()->id])->get('favoritee_id');
    }

    public function getRoommateRequestsProperty(): Collection
    {
        return $this->getAuthModel()->getRoommateRequests();
    }

    /** checks */
    protected function hasBeenFavorited(User $user): bool
    {
        return $this->favorites->pluck('favoritee_id')->contains($user->id);
    }

    protected function hasRoommateRequestFrom(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->sender->is($user);
            });
    }

    protected function hasSentRoommateRequestTo(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->recipient->is($user);
            });
    }

    protected function hasSentOrRecievedRoommateRequest(User $user): bool
    {
        return $this->roommateRequests
            ->contains(function (RoommateRequest $roommateRequest) use ($user) {
                return $roommateRequest->id === RoommateRequest::getCompositeKey($this->getAuthModel(), $user);
            });
    }

    protected function hasNotSentOrRecievedRoommateRequest(User $user): bool
    {
        return !$this->hasSentOrRecievedRoommateRequest($user);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.dashboard-filament');

        return $view->layout('layouts.guest');
    }
}
