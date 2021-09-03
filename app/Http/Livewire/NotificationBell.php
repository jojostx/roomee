<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public $requestNotifs;

    public function mount()
    {
    }

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            "echo-private:request.{$id},RoommateRequestUpdated" => 'showNotifs'
        ];
    }

    public function getUnseenNotifsProperty()
    {
        return auth()->user()->unreadNotifications->count();
    }

    public function showNotifs($data)
    {
        // dd($data);
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
