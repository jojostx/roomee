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
        $request->validate([
            'feedback' => 'required|boolean'
        ]);

        Feedback::create([
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'success' => 'Feedback submitted successfully',
        ], 201);

    }
}
