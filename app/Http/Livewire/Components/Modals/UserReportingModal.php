<?php

namespace App\Http\Livewire\Components\Modals;

use App\Models\User;
use App\Models\Report;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\CanRetrieveUser;
use App\Http\Livewire\Traits\WithReporting;

class UserReportingModal extends ModalComponent
{
    use WithReporting, CanRetrieveUser;

    public string | User $user;
    public array $selectedReports = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->report_ids = Report::query()->pluck('id')->map(fn ($value) => strval($value))->toArray();
    }

    protected function getAuthModel(): User
    {
        return Auth::user();
    }

    /** validation concerns and submit action */
    protected function rules(): array
    {
        return [
            'user' => ['required'],
            'selectedReports' => ['required', 'array'],
            'selectedReports.*' => ['required', 'numeric', Rule::in($this->report_ids)],
        ];
    }

    public function submit()
    {
        $this->validate();

        //saving data/reports/blocking into the database
        $this->reportUser($this->user, $this->selectedReports);;

        $this->emit('actionTakenOnUser');
        $this->closeModal();
    }

    
    /** UI definitions and triggers */
    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function render()
    {
        return view('livewire.components.modals.user-reporting-modal', [
            'reports' => Report::query()->pluck('description', 'id'),
        ]);
    }
}
