<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
  public function index()
  {
    $authUser = auth()->user();
    $users = User::gender($authUser->gender)
    ->school($authUser->school_id)->excludeUser($authUser->id)
    ->with('course')
    ->get();
    
    // dd($users);
   
    return view('dashboard', ['users' => $users]);
  }
}
