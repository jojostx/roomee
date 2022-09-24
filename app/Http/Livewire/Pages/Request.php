<?php

namespace App\Http\Livewire\Pages;

use App\Models\RoommateRequest;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class Request extends Component
{
    public Collection $recievedRequests;
    public Collection $sentRequests;
    public $currentPage = 'recieved';

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
        if ($this->currentPage === "sent") {
            $this->sentRequests = $this->fetchRequestsByType('sent');

            $this->recievedRequests = collect([]);
        } else {
            $this->recievedRequests = $this->fetchRequestsByType('recieved');

            $this->sentRequests = collect([]);
        }
    }

    public function fetchRequestsByType(string $requestType): Collection
    {
        $requestTypes = collect(['sent', 'recieved']);

        if (!$requestTypes->contains($requestType)) {
            return collect([]);
        }

        if ($requestType === 'sent') {
            return RoommateRequest::where('requester_id',  auth()->id())->with('recipient')->orderBy('created_at', 'desc')->get();
            // return RoommateRequest::where('requester_id',  auth()->id())->with('recipient:id,firstname,lastname,avatar,course_id')->orderBy('created_at', 'desc')->get();
        } else {
            return RoommateRequest::Where('requestee_id',  auth()->id())->with('sender')->orderBy('created_at', 'desc')->get();
            // return RoommateRequest::Where('requestee_id',  auth()->id())->with('sender:id,firstname,lastname,avatar,course_id')->orderBy('created_at', 'desc')->get();
        }
    }

    public function switchPage()
    {
        if ($this->currentPage === "sent") {
            $this->recievedRequests = $this->fetchRequestsByType('recieved');
            $this->currentPage = "recieved";
            return;
        }

        $this->sentRequests = $this->fetchRequestsByType('sent');
        $this->currentPage = "sent";
        return;
    }

    public function resetUsersWhenSentRequestIsDeleted($id)
    {
        $this->recievedRequests = $this->recievedRequests->except([$id]);
    }

    public function handleRoommateRequestUpdatedEvent($data)
    {
        $user = User::find($data['requester_id']);

        $this->emit('refreshChildren:' . $user->id);

        switch ($data['status']) {
            case 'deleted':
                break;
            case 'accepted':
                $this->showRecievedRequestToastNotification($user->fullname, 'request.Accepted');
                break;
            default:
                $this->showRecievedRequestToastNotification($user->fullname);
                break;
        }
    }

    // fires a card component refresh when another user blocks the currently authenticated user
    public function handleUserblockedEvent($data)
    {
        $this->emit('refreshChildren:' . $data['blocker_id']);
        $this->emit('resetUsers', $data['blocker_id']);
    }

    public function showRecievedRequestToastNotification($name, string $status = 'request.Recieved')
    {
        $this->emit('actionTakenOnUser', $name, $status);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.request');

        return $view->layout('layouts.guest');
    }
}
