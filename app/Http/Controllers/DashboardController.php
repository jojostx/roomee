<?php

namespace App\Http\Controllers;

use App\Models\Blocklist;
use App\Models\User;

class DashboardController extends Controller
{
  public function index()
  {
    $authUser = auth()->user();
    
    $blocklist = Blocklist::where('blocker_id', $authUser->id)->pluck('blockee_id')->toArray();
    
    $users = User::gender($authUser->gender)
    ->school($authUser->school_id)->excludeUser($authUser->id)->whereIntegerNotInRaw('id', $blocklist)
    ->with('course')
    ->get();
    
    // dd($users);
   
    return view('dashboard', ['users' => $users]);
  }
}
