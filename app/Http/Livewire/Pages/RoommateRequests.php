<?php

namespace App\Http\Livewire\Pages;

use App\Enums\RoommateRequestType;
use App\Http\Livewire\Traits\CanReactToRoommateRequestUpdate;
use App\Models\RoommateRequest;
use Illuminate\Support\Collection;
use Livewire\Component;

class RoommateRequests extends Component
{
    use CanReactToRoommateRequestUpdate;

    public Collection $recievedRoommateRequests;
    public Collection $sentRoommateRequests;
    public RoommateRequestType $currentPage = RoommateRequestType::RECIEVED;

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            'resetUsers' => 'resetUsersWhenSentRequestIsDeleted',
            "echo-private:roommate-request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }

    public function mount()
    {
        //remember to paginate
        if ($this->currentPage === RoommateRequestType::SENT) {
            $this->sentRoommateRequests = $this->fetchRequestsByType(RoommateRequestType::SENT);

            $this->recievedRoommateRequests = collect([]);
        } else {
            $this->recievedRoommateRequests = $this->fetchRequestsByType(RoommateRequestType::RECIEVED);

            $this->sentRoommateRequests = collect([]);
        }
    }

    public function switchPage(string $requestType)
    {
        $requestType = RoommateRequestType::tryFrom($requestType);

        switch ($requestType) {
            case RoommateRequestType::SENT:
                $this->sentRoommateRequests = $this->fetchRequestsByType(RoommateRequestType::SENT);
                $this->currentPage = RoommateRequestType::SENT;

                break;
            case RoommateRequestType::RECIEVED:
                $this->recievedRoommateRequests = $this->fetchRequestsByType(RoommateRequestType::RECIEVED);
                $this->currentPage = RoommateRequestType::RECIEVED;

                break;
            default:
                break;
        }
    }

    protected function fetchRequestsByType(RoommateRequestType $requestType): Collection
    {
        if ($requestType == RoommateRequestType::SENT) {
            return RoommateRequest::where('sender_id',  auth()->id())->with('recipient')->orderBy('created_at', 'desc')->get();
        }

        if ($requestType == RoommateRequestType::RECIEVED) {
            return RoommateRequest::Where('recipient_id',  auth()->id())->with('sender')->orderBy('created_at', 'desc')->get();
        }

        return collect([]);
    }

    protected function resetUsersWhenSentRequestIsDeleted($id)
    {
        $this->recievedRoommateRequests = $this->recievedRoommateRequests->except([$id]);
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
        $view = view('livewire.pages.roommate-requests');

        return $view->layout('layouts.guest');
    }
}
