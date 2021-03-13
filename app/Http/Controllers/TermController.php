<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;

class TermController extends Controller
{
    //
    public function show(){
        return view('pages.terms')->with('terms', ['man' => 'young']);
    }
}
