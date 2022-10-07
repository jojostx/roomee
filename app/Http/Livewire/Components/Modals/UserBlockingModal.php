<?php

namespace App\Http\Livewire\Components\Modals;

use App\Http\Livewire\Pages\Blocklist;
use App\Http\Livewire\Pages\Dashboard;
use App\Http\Livewire\Pages\Favorite;
use App\Http\Livewire\Pages\Profile\ViewProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;

class UserBlockingModal extends ModalComponent
{
    public string | User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function blockUser():bool
    {
        try {
            /** @var bool */
            $result = DB::transaction(function (): bool {
                return $this->getAuthModel()->block($this->user) &&
                    // delete any existing roommate request
                    $this->getAuthModel()->deleteRoommateRequest($this->user) &&
                    // remove user from favorites, delete sent and recieved requests
                    (bool) $this->getAuthModel()->favorites()->detach($this->user->getKey());
            });

            return $result;
        } catch (\Throwable $th) {
            return false;
        }
    }

    protected function unblockUser()
    {
        return $this->getAuthModel()->unblock($this->user);
    }

    public function submit()
    {
        if ($this->getAuthModel()->can('block', $this->user)) {
            $this->blockUser();
        };

        if ($this->getAuthModel()->can('unblock', $this->user)) {
            $this->unblockUser();
        };

        $this->closeModalWithEvents($this->getListenerComponents());
    }

    public static function getListenerComponents()
    {
        return [
            ViewProfile::getName() => 'actionTakenOnUser',
            Dashboard::getName() => 'actionTakenOnUser',
            Blocklist::getName() => 'actionTakenOnUser',
            Favorite::getName() => 'actionTakenOnUser',
        ];
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function render()
    {
        return view('livewire.components.modals.user-blocking-modal');
    }
}
