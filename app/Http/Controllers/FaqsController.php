<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function index(){
        return view('pages.faqs');
    }

    public function store(Request $request){

    //validate request
    //store request
    //return confirmation message
    $this->validate($request, [
        'feedback' => 'required|boolean',
    ]);

    Feedback::create([
        'feedback' => $request->feedback,
    ]);

    return response()->json([
        'success' => 'Feedback Submitted',
    ]);
    // return redirect('faqs')->with('status', 'Feedback Submitted!');
     
    }
}
