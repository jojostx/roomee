<?php

namespace App\Http\Controllers;

use App\Models\Blocklist;
use App\Models\User;

class DashboardController extends Controller
{
  public function index()
  { 
    // dd($users);
   
    return view('dashboard');
  }
}
