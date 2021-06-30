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
    public $auth_user_id;
    public string $user_id = '';
    public string $username = '';
    public bool $show = false;
    public array $selectedReports = [];
    public $reports;
    public $report_ids;

    //[block|report]
    public $action = '';
    public $actions = ['block', 'report'];

    protected $listeners = ['blockOrReport' => 'handleIssue'];

    public function mount()
    {
        $this->auth_user_id = auth()->user()->id;

        //all the issues that a user can be reported for
        $this->reports = Report::pluck('description', 'id');

        $this->report_ids = $this->reports->map(function ($item, $key) {
            return strval($key);
        })->toArray();
    }

    public function handleIssue($id, $fullname)
    {
        $this->user_id = $id;
        $this->username = $fullname;
        $this->action = "";
        $this->show = true;
    }

    public function reset_()
    {
        $this->resetValidation();
        $this->user_id = '';
        $this->username = '';
        $this->action = "";
        $this->show = false;
        $this->selectedReports = [];
    }

    protected function rules()
    {
        if ($this->action !== "report") {
            return [
                'action' => ['required', 'in_array:actions.*'],
                'user_id' => ['required', 'numeric', new IsBlockable()],
            ];
        }

        return [
            'action' => ['required', 'in_array:actions.*'],
            'selectedReports' => ['required', 'array'],
            'selectedReports.*' => ['required', 'numeric', 'in_array:report_ids.*'],
            'user_id' => ['required', 'numeric', 'not_in:' . auth()->user()->id, new IsReportable()],
        ];
    }

    protected array $messages = [
        'selectedReports' => 'choose at least one report',
        'selectedReports.*' => 'choose at least one report',
    ];

    public function submit()
    {
        $this->validate();

        //saving data into the database
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
                    $timestamp = now()->toDateTimeString();

                    $id = DB::table('blocklists')->insertGetId([
                        'blocker_id' => auth()->user()->id,
                        'blockee_id' => intval($this->user_id),
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);

                    if ($id) {
                        $this->emit('actionTakenOnUser', $this->username, $this->action);
                    }
                    //1. emit an event up to refresh the user lists that is being displayed in
                    // the dashboard UI (done successfully)
                    // 2. show a toast notification
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
