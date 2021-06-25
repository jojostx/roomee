<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Rules\IsBlockable;
use App\Rules\IsReportable;
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
        $this->reports = Report::all();
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
            'user_id' => ['required', 'numeric', 'not_in:'.auth()->user()->id, new IsReportable()],
        ];
       
    }

    public function submit()
    {
        $this->validate();

        dd($this->user_id, $this->action);

        $this->reset_();
    }

    public function render()
    {
        return view('livewire.issues-modal');
    }
}
