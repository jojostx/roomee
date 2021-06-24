<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsBlockable implements Rule
{

    protected $blocklist;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->blocklist = auth()->user();
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
        return strval($this->blocker_id) !== strval($value);
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
