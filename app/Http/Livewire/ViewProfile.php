<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewProfile extends Component
{
    public $user;
    public $blocklist_id;

    public function mount(User $user)
    {
    
        $this->user = $user;
    
    }

    public function blockUser()
    {
        if ($this->user->id === auth()->user()->id) {
            return;
        }

        $timestamp = now()->toDateTimeString();

        $id = DB::table('blocklists')->insertGetId([
            'blocker_id' => auth()->user()->id,
            'blockee_id' => intval($this->user->id),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        if ($id) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'block');
            $this->blocklist_id = $id;
        }
    }

    public function unblockUser()
    {
        if ($this->user->id === auth()->user()->id) {
            return;
        }

        $id = DB::table('blocklists')->delete($this->blocklist_id);

        if ($id) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');
            $this->blocklist_id = "";
        }
    }

    public function render()
    {
        return view('livewire.view-profile')->layout('layouts.guest');
    }
}
