<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompliantFromContactPageRequest;
use App\Models\Contact;
use http\Client\Response;

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
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json(['success' => 'Message Submitted succesfully'], 201);
    }
}
