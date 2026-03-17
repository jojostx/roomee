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
use Closure;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use App\Models\User;
use App\Models\Report;
use App\Livewire\Traits;
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

class BlocklistPage extends Component implements HasTable, HasForms, HasActions
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
            ...$this->getBlockingActions(),

            ActionGroup::make([
                ...$this->getReportingAction(),
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
    #[Computed]
    public function blockedUsers(): Collection
    {
        return DB::table('blocklists')->where(['blocker_id' => $this->getAuthModel()->id])->get('blockee_id');
    }

    // actions
    protected function getReportingAction()
    {
        return [
            Action::make('report')
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
            Action::make('unblock')
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
                    $this->dispatchSelf('refresh-component');
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

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        // Return null if not using translations
        return null;
    }
}
