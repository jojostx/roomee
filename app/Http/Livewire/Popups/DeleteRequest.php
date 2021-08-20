<?php

namespace App\Http\Livewire\Popups;

use Livewire\Component;

class DeleteRequest extends Component
{ 
    public bool $show = false;
    public string $user_id = '';
    public string $name = '';

    protected $listeners = ['showDeleteRequestPopup' => 'AssignVariables'];

    public function AssignVariables($id, $name): void
    {
        list($this->user_id, $this->name, $this->show) = [$id, $name, true];
    }
    
    public function reset_(): void
    {
        $this->user_id = "";
        $this->username = "";
        $this->show = false;
    }

    public function deleteRequest()
    {
        if ((int) auth()->id() !== (int) $this->user_id) {
            auth()->user()->sentRequests()->detach($this->user_id);
            
            $this->emit("refreshChildren:{$this->user_id}");

            $this->reset_();
        }
    }

    public function render()
    {
        return view('livewire.popups.delete-request');
    }
}
