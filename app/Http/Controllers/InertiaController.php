<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaController extends Controller
{
    public function show()
    {
        return Inertia::render('Welcome', [
           'foo' => 'bar'
        ]);
    }
}
