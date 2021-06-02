<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'firstname' => ['required', 'alpha', 'string', 'max:255'],
            'lastname' => ['required', 'alpha','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'password' => ['required', 'string','confirmed','min:8'],
            'gender' => ['required', Rule::in(['male', 'female'])]
        ]);

        Auth::login($user = User::create([
            'firstname' => Str::of($request->firstname)->ucfirst(),
            'lastname' => Str::of($request->lastname)->ucfirst(),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
        ]));

        event(new Registered($user));

        return redirect(RouteServiceProvider::PROFILE);
    }
}
