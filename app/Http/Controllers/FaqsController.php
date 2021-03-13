<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqsController extends Controller
{
    public function index()
    {
        return view('pages.faqs');
    }

    public function store(Request $request)
    {
//        $request->validate([
//            'feedback' => 'required|boolean',
//        ]);

        $validator = Validator::make($request->all(),[
            'feedback' => 'required|boolean',
        ]);

        if ($validator->fails()){
            return  response(['error'=>$validator->errors()->all()], 442);
        }

        Feedback::create([
            'feedback' => $request->feedback,
        ]);


        return response()->json([
            'success' => 'Feedback submitted succesfully',
        ]);

    }
}
