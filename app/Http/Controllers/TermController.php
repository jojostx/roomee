<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;

class TermController extends Controller
{
    //
    public function show(){
        $terms = Term::all();
        // \dd($terms);
        // foreach (Term::all() as $term) {
        //     \dd($term->term_body);          
        // }
        return view('pages.terms')->with('terms', $terms);
    }
}
