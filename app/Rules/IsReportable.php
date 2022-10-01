<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class IsReportable implements Rule
{
    public Collection $reportable_users;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user = auth()->user();

        $this->reportable_users = User::query()
            ->gender($user->gender)
            ->school($user->school_id)
            ->excludeUser($user->id)
            ->get();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->reportable_users->contains($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot report this user';
    }
}
