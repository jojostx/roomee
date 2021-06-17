<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class ModelsExist implements Rule
{
    /** @var string */
    protected $modelClassName;

    /** @var string */
    protected $modelAttribute;

    /** @var string */
    protected $attribute;

    /** @var array */
    protected $modelIds;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $modelClassName, string $attribute = 'id')
    {
        $this->modelClassName = $modelClassName;

        $this->modelAttribute = $attribute;
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
        $this->attribute = $attribute;

        // if (($value instanceof Model) && $value->id) {
        //     $modelCount = $this->modelClassName::whereIn('id', [$value->id])->count();
        //     return $modelCount === 1;
        // }

        $value = array_filter($value);

        $this->modelIds = array_unique($value);

        $modelCount = $this->modelClassName::whereIn($this->modelAttribute, $this->modelIds)->count();

        return count($this->modelIds) === $modelCount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Some of the given ids do not exist.';
    }
}
