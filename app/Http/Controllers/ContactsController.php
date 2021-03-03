<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {

        //validate request
        //store request
        //return confirmation message
        $this->validate($request, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'message' => 'required|max:255'
        ]);

        Contact::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => 'Message Submitted',
        ]);
        // return redirect('faqs')->with('status', 'Feedback Submitted!');

    }
}
