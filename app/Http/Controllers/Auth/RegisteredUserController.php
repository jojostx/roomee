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
use Illuminate\Validation\Rules\Password;
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
            'first_name' => ['required', 'alpha', 'string', 'max:255'],
            'last_name' => ['required', 'alpha','string', 'max:255'],
            'email' => ['required','string','email', 'max:255','unique:users'],
            'password' => ['required', 'confirmed', 'max:100', Password::defaults()],
            'gender' => ['required', Rule::in(['male', 'female'])]
        ]);

        Auth::login($user = User::create([
            'first_name' => Str::of($request->first_name)->ucfirst(),
            'last_name' => Str::of($request->last_name)->ucfirst(),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
        ]));

        event(new Registered($user));

        return redirect(RouteServiceProvider::PROFILE);
    }
}
