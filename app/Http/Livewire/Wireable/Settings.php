<?php

namespace App\Http\Livewire\Wireable;

use Livewire;

class Settings implements Livewire\Wireable
{
    public array $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function toLivewire()
    {
        return $this->items;
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
