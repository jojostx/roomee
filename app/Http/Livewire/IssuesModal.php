<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Rules\IsBlockable;
use App\Rules\IsReportable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class IssuesModal extends Component
{
    public string $user_id = '';
    public string $username = '';
    public bool $show = false;
    public $reports;
    public array $selectedReports = [];
    //[block|report]
    public $action = '';

    protected $listeners = ['blockOrReport' => 'handleIssue'];

    public function mount()
    {
        $this->reports = Report::pluck('description', 'id');  
    }

    //sets the id, username and show properties to their required properties
    //handleIssue is called when a "block" or "report" event is fired from the blade/html page
    public function handleIssue($id, $fullname)
    {
        list($this->user_id, $this->username, $this->show ) = [$id, $fullname, true];
    }

    //resets all the properties including validation/errors
    //called when the modal is closed or after the issue ("block" or "report") is saved to the database
    public function reset_()
    {
        $this->resetValidation();
        $this->user_id = "";
        $this->username = "";
        $this->action = "";
        $this->show = false;
        $this->selectedReports = [];
    }

    protected function rules()
    {
        $actions = ['block', 'report'];
        $report_ids = Report::pluck('id')->map(fn ($value) => strval($value))->toArray();  

        if ($this->action !== "report") {
            return [
                'action' => ['required', Rule::in($actions)],
                'user_id' => ['required', 'numeric', new IsBlockable()],
            ];
        }

        return [
            'action' => ['required', Rule::in($actions)],
            'selectedReports' => ['required', 'array'],
            'selectedReports.*' => ['required', 'numeric', Rule::in($report_ids)],
            'user_id' => ['required', 'numeric', 'not_in:' . auth()->user()->id, new IsReportable()],
        ];
    }

    protected array $messages = [
        'selectedReports' => 'choose at least one report',
        'selectedReports.*' => 'choose at least one report',
    ];

    //performs the submission of reports and blocking of user actions by saving to the database
    // it additionally emits an event after successfully saving to database; for each possible action taken
    public function submit()
    {
        $this->validate();

        //saving data/reports/blocking into the database
        switch ($this->action) {
            case 'report': {
                    DB::table('report_user')->insert(array_map(function ($item) {
                        $timestamp = now()->toDateTimeString();

                        return [
                            'reporter_id' => auth()->user()->id,
                            'reportee_id' => intval($this->user_id),
                            'report_id' => intval($item),
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp,
                        ];
                    }, $this->selectedReports));

                    $this->emit('actionTakenOnUser', $this->username, $this->action);
                    break;
                }

            case 'block': {
                                       
                    auth()->user()->blocklists()->attach($this->user_id);
                    auth()->user()->favorites()->detach($this->user_id);
                    
                    $this->emit('actionTakenOnUser', $this->username, $this->action);
                    
                    break;
                }

            default:
                break;
        }

        $this->reset_();
    }

    public function render()
    {
        return view('livewire.issues-modal');
    }
}
