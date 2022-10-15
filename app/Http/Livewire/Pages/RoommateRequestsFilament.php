<?php

namespace App\Http\Livewire\Pages;

use App\Enums\RoommateRequestType;
use App\Http\Livewire\Traits\CanReactToRoommateRequestUpdate;
use App\Models\RoommateRequest;
use Illuminate\Support\Collection;
use Livewire\Component;
use Filament\Tables;
use App\Http\Livewire\Traits;
use App\Models\Report;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;


class RoommateRequestsFilament extends Component implements Tables\Contracts\HasTable
{
  use CanReactToRoommateRequestUpdate;
  use
    Traits\WithFavoriting,
    Traits\WithRequesting,
    Traits\WithBlocking,
    Traits\CanRetrieveUser,
    Tables\Concerns\InteractsWithTable {
    applySortingToTableQuery as parentApplySortingToTableQuery;
  }

  protected function getListeners()
  {
    $id = auth()->id();

    return [
      'refresh:component' => '$refresh',
      'actionTakenOnUser' => '$refresh',
      'resetUsers' => 'resetUsersWhenSentRoommateRequestIsDeleted',
      "echo-private:roommate-request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
      "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
    ];
  }

  // fires a card component refresh when another user blocks the currently authenticated user
  protected function handleUserblockedEvent($data)
  {
    $this->emit('refreshChildren:' . $data['blocker_id']);
    $this->emit('resetUsers', $data['blocker_id']);
  }

  public function getAuthModel(): ?User
  {
    return Auth::user();
  }

  protected function getTableQuery(): Builder | Relation
  {
    // \dd($this->getAuthModel()->allPotentialRoommates()->withCasts(['sent_at' => 'datetime'])->first()->sent_at);
    // \dd(auth()->id(), $this->getAuthModel()->allPotentialRoommates()->get());
    // \dd($this->getAuthModel()->allRoommateRequests()->getRelation('sender', 'recipient')->get());
    // return $this->getAuthModel()->allRoommateRequests();
    return $this->getAuthModel()->allPotentialRoommates()->getQuery();
  }

  protected function getTableFilters(): array
  {
    return [
      Tables\Filters\TernaryFilter::make('requests')
        ->placeholder('All')
        ->trueLabel('Recieved')
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
            Tables\Columns\TextColumn::make('pivot_created_at')
              ->formatStateUsing(fn ($state) => filled($state) ? Date::parse($state)->setTimezone('WAT') : null)
              ->sortable(),

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
      ...$this->getFavoritingActions(),

      Tables\Actions\ActionGroup::make([
        ...$this->getReportingAction(),
        ...$this->getBlockingActions(),
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
      'lg' => 3,
    ];
  }

  protected function getTableRecordClassesUsing(): ?Closure
  {
      return fn (User $record) => match (true) {
          $this->hasAcceptedRoommateRequest($record) => 'filament-user-card roommate-request-accepted',
          $this->hasPendingRoommateRequestFrom($record) => 'filament-user-card roommate-request-recieved',
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
    return 'You have not sent or recieved any Roommate Requests.';
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

  protected function hasPendingRoommateRequestTo(User $user): bool
  {
    return $this->roommateRequests
      ->contains(function (RoommateRequest $roommateRequest) use ($user) {
        return $roommateRequest->recipient->is($user) && $roommateRequest->isPending();
      });
  }

  protected function hasSentOrRecievedRoommateRequest(User $user): bool
  {
    return $this->roommateRequests
      ->contains(function (RoommateRequest $roommateRequest) use ($user) {
        return $roommateRequest->id === RoommateRequest::getCompositeKey($this->getAuthModel(), $user);
      });
  }

  protected function hasNoSentOrRecievedRoommateRequest(User $user): bool
  {
    return !$this->hasSentOrRecievedRoommateRequest($user);
  }

  protected function hasPendingSentOrRecievedRoommateRequest(User $user): bool
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
          $this->emitSelf('refresh:component');
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
      Tables\Actions\Action::make('favorite')
        ->iconButton()
        ->label('Add to Favorites')
        ->tooltip('Add to Favorites')
        ->color('secondary')
        ->icon('heroicon-o-star')
        ->action(function (User $record) {
          $this->favorite($record);
          $this->emitSelf('refresh:component');
        })
        ->extraAttributes([
          'title' => 'Favorite button',
          'class' => 'border border-secondary-300',
          'style' => 'width: 3rem; border-radius: .5rem',
          'id' => 'filament-tables-action-favorite'
        ])
        ->visible(fn (User $record): bool => !$this->hasBeenFavorited($record)),

      Tables\Actions\Action::make('unfavorite')
        ->iconButton()
        ->label('Remove from Favorites')
        ->tooltip('Remove from Favorites')
        ->color('primary')
        ->icon('heroicon-s-star')
        ->action(function (User $record) {
          $this->unfavorite($record);
          $this->emitSelf('refresh:component');
        })
        ->extraAttributes([
          'title' => 'Favorite button',
          'class' => 'border border-secondary-300',
          'style' => 'width: 3rem; border-radius: .5rem',
          'id' => 'filament-tables-action-favorite'
        ])
        ->visible(fn (User $record): bool => $this->hasBeenFavorited($record))
    ];
  }

  protected function getRoommateRequestingActions()
  {
    return [
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
          $this->emitSelf('refresh:component');
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
          $this->emitSelf('refresh:component');
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
        ->requiresConfirmation()
        ->visible(fn (User $record) => $this->hasAcceptedRoommateRequest($record)),
    ];
  }

  public function render()
  {
    /** @var \Illuminate\View\View */
    $view = view('livewire.pages.roommate-requests-filament');

    return $view->layout('layouts.guest');
  }
}
