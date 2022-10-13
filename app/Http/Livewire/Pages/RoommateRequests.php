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
            'resetUsers' => 'resetUsersWhenSentRoommateRequestIsDeleted',
            "echo-private:roommate-request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }

    public function mount()
    {
        //remember to paginate
        if ($this->currentPage === RoommateRequestType::SENT) {
            $this->sentRoommateRequests = $this->fetchRoommateRequestsByType(RoommateRequestType::SENT);

            $this->recievedRoommateRequests = collect([]);
        } else {
            $this->recievedRoommateRequests = $this->fetchRoommateRequestsByType(RoommateRequestType::RECIEVED);

            $this->sentRoommateRequests = collect([]);
        }
    }

    public function switchPage(string $roommateRequestType)
    {
        $roommateRequestType = RoommateRequestType::tryFrom($roommateRequestType);

        switch ($roommateRequestType) {
            case RoommateRequestType::SENT:
                $this->sentRoommateRequests = $this->fetchRoommateRequestsByType(RoommateRequestType::SENT);
                $this->currentPage = RoommateRequestType::SENT;

                break;
            case RoommateRequestType::RECIEVED:
                $this->recievedRoommateRequests = $this->fetchRoommateRequestsByType(RoommateRequestType::RECIEVED);
                $this->currentPage = RoommateRequestType::RECIEVED;

                break;
            default:
                break;
        }
    }

    protected function fetchRoommateRequestsByType(RoommateRequestType $roommateRequestType): Collection
    {
        if ($roommateRequestType == RoommateRequestType::SENT) {
            return RoommateRequest::where('sender_id',  auth()->id())->with('recipient')->orderBy('created_at', 'desc')->get();
        }

        if ($roommateRequestType == RoommateRequestType::RECIEVED) {
            return RoommateRequest::Where('recipient_id',  auth()->id())->with('sender')->orderBy('created_at', 'desc')->get();
        }

        return collect([]);
    }

    protected function resetUsersWhenSentRoommateRequestIsDeleted($id)
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
