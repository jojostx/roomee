<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\ClosesModal;
use App\Livewire\Traits\WithReporting;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserReportingModal extends Component
{
    use WithReporting, CanRetrieveUser, ClosesModal;

    public string | User $user;
    public array $selectedReports = [];
    public array $report_ids;

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

    protected array $messages = [
        'selectedReports' => 'choose at least one report',
        'selectedReports.*' => 'choose at least one report',
    ];

    public function submit()
    {
        $this->validate();

        //saving data/reports/blocking into the database
        $this->reportUser($this->user, $this->selectedReports);;

        $this->dispatch('actionTakenOnUser');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.components.modals.user-reporting-modal', [
            'reports' => Report::query()->pluck('description', 'id'),
        ]);
    }
}
