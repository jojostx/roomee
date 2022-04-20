<?php

namespace App\Http\Livewire\Components;

use Illuminate\Support\Facades\DB;
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
        $unseenNotifs = DB::table('notifications')->where([
            'notifiable_id' => auth()->id(),
            'notifiable_type' => 'App\Models\User',
            'read_at' => null
        ])->count();

        return $unseenNotifs;
    }

    public function showNotifs($data)
    {
        // dd($data);
    }

    public function render()
    {
        return view('livewire.components.notification-bell');
    }
}
