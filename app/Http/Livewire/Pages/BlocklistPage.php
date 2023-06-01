<?php

namespace App\Http\Livewire\Pages;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\User;
use App\Models\Report;
use App\Http\Livewire\Traits;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BlocklistPage extends Component implements Tables\Contracts\HasTable
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
            'actionTakenOnUser' => '$refresh'
        ];
    }

    public function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder | Relation
    {
        return $this->getAuthModel()->blocklists()->getQuery();
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
            ...$this->getBlockingActions(),

            Tables\Actions\ActionGroup::make([
                ...$this->getReportingAction(),
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
        return fn () => 'filament-user-card user-blocked';
    }

    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Users Found';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'You have not blocked any user';
    }

    /** dynamic properties */
    public function getBlockedUsersProperty(): Collection
    {
        return DB::table('blocklists')->where(['blocker_id' => $this->getAuthModel()->id])->get('blockee_id');
    }

    // actions
    protected function getReportingAction()
    {
        return [
            Tables\Actions\Action::make('report')
                ->button()
                ->label('Report User')
                ->icon('heroicon-o-flag')
                ->color('warning')
                ->extraAttributes([
                    'title' => 'report user',
                    'class' => 'w-full filament-tables-action-report-user',
                ])
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
            Tables\Actions\Action::make('unblock')
                ->button()
                ->label('Unblock User')
                ->icon('heroicon-o-lock-open')
                ->color('danger')
                ->extraAttributes([
                    'title' => 'unblock user',
                    'class' => 'w-full filament-tables-action-unblock-user',
                ])
                ->action(function (User $record) {
                    $this->unblockUser($record);
                    $this->emitSelf('refresh-component');
                })
                ->requiresConfirmation()
                ->modalHeading(fn (User $record) => 'Unblock ' . $record->full_name)
                ->modalContent(fn (User $record) => str("<p class='text-center'>This will allow <span class='font-semibold text-secondary-600'>{$record->full_name}</span> to view your profile and send you Roommate requests.</p>")->toHtmlString()),
        ];
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.blocklist-page');

        return $view->layout('layouts.guest');
    }
}
