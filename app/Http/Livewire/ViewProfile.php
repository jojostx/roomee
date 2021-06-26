<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;

class ViewProfile extends Component
{
    public $user;

    public function mount(Request $request, User $user)
    {
    
        $this->user = $user;

        // dd($user, $request->user()->can('view', $user));
    
    }

    public function render()
    {
        return view('livewire.view-profile')->layout('layouts.guest');
    }
}
