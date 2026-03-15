<?php

namespace App\Livewire\Components\Modals;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use App\Models\User;
use App\Models\Report;
use App\Enums\OnUserAction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use App\Livewire\Traits\ClosesModal;
use App\Livewire\Traits\WithBlocking;
use App\Livewire\Traits\WithReporting;
use App\Livewire\Traits\CanRetrieveUser;
use Filament\Forms\Concerns\InteractsWithForms;
use Livewire\Component;

class ReportOrBlockModal extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use WithBlocking, WithReporting, CanRetrieveUser, ClosesModal, InteractsWithForms;

    public ?OnUserAction $action = null;
    public string | User $user;
    public array $selectedReports = [];
    public array $report_ids;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->report_ids = Report::query()->pluck('id')->map(fn ($value) => strval($value))->toArray();
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    /** validation concerns and submit action */
    protected function rules(): array
    {
        if ($this->action == OnUserAction::BLOCK) {
            return [
                'user' => ['required'],
                'action' => ['required'],
            ];
        } else if ($this->action == OnUserAction::REPORT) {
            return [
                'user' => ['required'],
                'action' => ['required'],
                'selectedReports' => ['required', 'array'],
                'selectedReports.*' => ['required', 'numeric', Rule::in($this->report_ids)],
            ];
        } else {
            return [];
        }
    }

    protected array $messages = [
        'selectedReports' => 'choose at least one report',
        'selectedReports.*' => 'choose at least one report',
    ];

    public function submit()
    {
        $this->validate();

        //saving data/reports/blocking into the database
        switch ($this->action) {
            case OnUserAction::REPORT: {
                    $this->reportUser($this->user, $this->selectedReports);
                    break;
                }

            case OnUserAction::BLOCK: {
                    $this->blockUser($this->user);
                    break;
                }

            default:
                break;
        }

        $this->dispatch('actionTakenOnUser');
        $this->closeModal();
    }

    /** UI definitions and triggers */
    public function triggerblockUserAction()
    {
        $this->action = OnUserAction::BLOCK;
    }

    public function triggerReportUserAction()
    {
        $this->action = OnUserAction::REPORT;
    }

    public function render()
    {
        return view('livewire.components.modals.report-or-block-modal', [
            'reports' => Report::query()->pluck('description', 'id'),
        ]);
    }
}
