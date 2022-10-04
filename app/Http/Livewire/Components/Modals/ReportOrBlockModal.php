<?php

namespace App\Http\Livewire\Components\Modals;

use App\Enums\OnUserAction;
use App\Models\Report;
use App\Models\User;
use App\Rules\IsBlockable;
use App\Rules\IsReportable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class ReportOrBlockModal extends ModalComponent implements HasForms
{
    use InteractsWithForms;

    public string | User $user;
    public ?OnUserAction $action = null;
    public Collection $reports;
    public array $selectedReports = [];

    protected array $messages = [
        'selectedReports' => 'choose at least one report',
        'selectedReports.*' => 'choose at least one report',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->reports = Report::pluck('desc', 'id');
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function blockUser()
    {
        $this->action = OnUserAction::BLOCK;
    }

    public function reportUser()
    {
        $this->action = OnUserAction::REPORT;
    }

    public function getReportIdsProperty()
    {
        return Report::query()->pluck('id')->map(fn ($value) => strval($value))->toArray();
    }

    protected function rules(): array
    {
        if ($this->action == OnUserAction::BLOCK) {
            return [
                'action' => ['required'],
                'user' => ['required', new IsBlockable()],
            ];
        } else if ($this->action == OnUserAction::BLOCK) {
            return [
                'action' => ['required'],
                'selectedReports' => ['required', 'array'],
                'selectedReports.*' => ['required', 'numeric', Rule::in($this->report_ids)],
                'user' => ['required', new IsReportable()],
            ];
        } else {
            return [];
        }
    }

    //performs the submission of reports and blocking of user actions by saving to the database
    // it additionally emits an event after successfully saving to database; for each possible action taken
    public function submit()
    {
        $this->validate();

        //saving data/reports/blocking into the database
        switch ($this->action) {
            case OnUserAction::REPORT: {
                    DB::table('report_user')->insert(array_map(function ($item) {
                        $timestamp = now();

                        return [
                            'reporter_id' => $this->getAuthModel()->id,
                            'reportee_id' => intval($this->user),
                            'report_id' => intval($item),
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ];
                    }, $this->selectedReports));

                    $this->sendNotification($this->action);

                    break;
                }

            case OnUserAction::BLOCK: {
                    //block user 
                    $this->getAuthModel()->block($this->user);

                    // delete any existing roommate request
                    $this->getAuthModel()->deleteRoommateRequest($this->user);

                    // remove user from favorites, delete sent and recieved requests
                    $this->getAuthModel()->favorites()->detach($this->user->getKey());

                    //emit the event to show toast notification //js notif.
                    $this->sendNotification($this->action);

                    break;
                }

            default:
                break;
        }

        $this->closeModal();
    }

    protected function sendNotification(OnUserAction $action)
    {
        switch ($action) {
            case OnUserAction::REPORT: {
                    Notification::make()
                        ->title("Report submitted succesfully")
                        ->title("Your report has been submitted. Our team will review your report ASAP. Thanks!")
                        ->success()
                        ->send();

                    break;
                }

            case OnUserAction::BLOCK: {
                    Notification::make()
                        ->title("User blocked succesfully")
                        ->title("You have succesfully blocked **{$this->user->fullname}**. They will be unable to view your profile or send you roommate request.")
                        ->success()
                        ->send();

                    break;
                }

            default:
                break;
        }

        $this->emit('actionTakenOnUser', $this->user->fullname, $action);
    }

    public function render()
    {
        return view('livewire.components.modals.report-or-block-modal');
    }
}
