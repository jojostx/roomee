<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
use Filament\Notifications\Notification;

trait WithReporting
{
  abstract protected function getAuthModel(): ?User;
  abstract protected function retrieveUser(): ?User;

  public function reportUser($user_id = null, array $report_ids = [])
  {
    $user = $this->retrieveUser($user_id);

    if (blank($user) || blank($report_ids) || !($user instanceof User) || !$this->canBeReported($user)) {
      return;
    }

    if ($this->getAuthModel()->reportUser($this->user, $report_ids)) {
      Notification::make()
        ->title("Report submitted succesfully")
        ->body("Your report has been submitted. Our team will review your report ASAP. Thanks!")
        ->success()
        ->send();
    }
  }

  protected function canBeReported(User $user): bool
  {
    return $this->getAuthModel()->isValidUser($user);
  }
}
