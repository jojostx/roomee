<?php

namespace App\Http\Livewire\Pages;

use App\Enums\RequestType;
use App\Enums\RoommateRequestStatus;
use App\Http\Livewire\Traits\CanReactToRoommateRequestUpdate;
use App\Models\RoommateRequest;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Livewire\Component;

class Request extends Component
{
    use CanReactToRoommateRequestUpdate;

    public Collection $recievedRequests;
    public Collection $sentRequests;
    public RequestType $currentPage = RequestType::RECIEVED;

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            'resetUsers' => 'resetUsersWhenSentRequestIsDeleted',
            "echo-private:request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }

    public function mount()
    {
        //remember to paginate
        if ($this->currentPage === RequestType::SENT) {
            $this->sentRequests = $this->fetchRequestsByType(RequestType::SENT);

            $this->recievedRequests = collect([]);
        } else {
            $this->recievedRequests = $this->fetchRequestsByType(RequestType::RECIEVED);

            $this->sentRequests = collect([]);
        }
    }

    public function switchPage(string $requestType)
    {
        $requestType = RequestType::tryFrom($requestType);

        switch ($requestType) {
            case RequestType::SENT:
                $this->sentRequests = $this->fetchRequestsByType(RequestType::SENT);
                $this->currentPage = RequestType::SENT;

                break;
            case RequestType::RECIEVED:
                $this->recievedRequests = $this->fetchRequestsByType(RequestType::RECIEVED);
                $this->currentPage = RequestType::RECIEVED;

                break;
            default:
                break;
        }
    }

    protected function fetchRequestsByType(RequestType $requestType): Collection
    {
        if ($requestType == RequestType::SENT) {
            return RoommateRequest::where('requester_id',  auth()->id())->with('recipient')->orderBy('created_at', 'desc')->get();
        }

        if ($requestType == RequestType::RECIEVED) {
            return RoommateRequest::Where('requestee_id',  auth()->id())->with('sender')->orderBy('created_at', 'desc')->get();
        }

        return collect([]);
    }

    protected function resetUsersWhenSentRequestIsDeleted($id)
    {
        $this->recievedRequests = $this->recievedRequests->except([$id]);
    }

    // fires a card component refresh when another user blocks the currently authenticated user
    protected function handleUserblockedEvent($data)
    {
        $this->emit('refreshChildren:' . $data['blocker_id']);
        $this->emit('resetUsers', $data['blocker_id']);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.request');

        return $view->layout('layouts.guest');
    }
}
