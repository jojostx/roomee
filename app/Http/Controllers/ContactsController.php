<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompliantFromContactPageRequest;
use App\Models\Contact;

class ContactsController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(StoreCompliantFromContactPageRequest $request)
    {
        $request->validated();

        Contact::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json(['success' => 'Message Submitted succesfully'], 201);
    }
}
