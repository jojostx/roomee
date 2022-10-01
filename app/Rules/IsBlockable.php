<?php

namespace App\Rules;

use App\Models\Blocklist;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class IsBlockable implements Rule
{

    protected $blocklist;
    protected $blockable_users;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        $this->blocklist = Blocklist::query()
            ->where('blocker_id', $user->id)
            ->pluck('blockee_id')
            ->toArray();

        $this->blockable_users = User::query()
            ->gender($user->gender)
            ->school($user->school_id)
            ->excludeUser($user->id)
            ->whereIntegerNotInRaw('id', $this->blocklist)
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
        return $this->blockable_users->contains($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot block this user';
    }
}
