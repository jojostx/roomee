<?php

namespace App\Http\Livewire\Components\Modals;

use App\Http\Livewire\Pages\Blocklist;
use App\Http\Livewire\Pages\Dashboard;
use App\Http\Livewire\Pages\Favorite;
use App\Http\Livewire\Pages\Profile\ViewProfile;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class UserReportingModal extends ModalComponent
{
    public string | User $user;
    public array $selectedReports = [];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function rules(): array
    {
        return [
            'user' => ['required'],
            'selectedReports' => ['required', 'array'],
            'selectedReports.*' => ['required', 'numeric', Rule::in($this->report_ids)],
        ];
    }

    protected function getAuthModel(): User
    {
        return Auth::user();
    }

    public function getReportsProperty()
    {
        return Report::query()->pluck('description', 'id');
    }

    public function getReportIdsProperty()
    {
        return Report::query()->pluck('id')->map(fn ($value) => strval($value))->toArray();
    }

    public function getIsReportableProperty()
    {
        $authUser = $this->getAuthModel();

        return User::query()
            ->where('id', $this->user->id)
            ->gender($authUser->gender)
            ->school($authUser->school_id)
            ->excludeUser($authUser->id)
            ->exists();
    }

    protected function reportUser()
    {
        return DB::table('report_user')
            ->insert(array_map(function ($item) {
                $timestamp = now();

                return [
                    'reporter_id' => $this->getAuthModel()->id,
                    'reportee_id' => intval($this->user->id),
                    'report_id' => intval($item),
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }, $this->selectedReports));
    }

    public function submit()
    {
        if (!$this->is_reportable) {
            $this->addError('user', 'You cannot report this user');
            return;
        }

        $this->validate();

        //saving data/reports/blocking into the database
        $this->reportUser();

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
        return view('livewire.components.modals.user-reporting-modal');
    }
}
