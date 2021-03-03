<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Faq extends Component
{

    /**
     * 
     * An asscoiative array of Questions and Answers
     * 
     */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.faq');
    }
}
