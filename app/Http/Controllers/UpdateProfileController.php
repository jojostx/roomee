<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateProfileController extends Controller
{

    /**
     *
     */
    public function index(){

        $roles = User::find(1)->hobbies()->orderBy('name')->get();
        $string_roles = [];

        foreach ($roles as $role) {
            array_push($string_roles, $role->name);
        }

        $hobbies = Hobby::orderBy('id')->get();
        $hobbies->each(function ($hobby){
            if ($hobby->id > 4) $hobby->checked = true;
        });
        $hobbies = $hobbies->except([1]);;

        dd($hobbies);
    }
}
