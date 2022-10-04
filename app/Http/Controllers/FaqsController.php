<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Repositories\FaqRepository;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    public function index()
    {
        return view('pages.faqs', ['groups' => FaqRepository::getFaqskeyedByCategories()]);
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
