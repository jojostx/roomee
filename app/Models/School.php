<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    /**
     * The courses that belongs to this school.
     */
    public function courses(){
        return  $this->belongsToMany(Course::class, 'course_school')->withTimestamps();
    }
    

    /**
     * The towns that belong to this school.
     */
    public function towns(){
        return  $this->hasMany(Town::class);
    }

    /**
     * The users that belong to this school.
     */
    public function users(){
        return  $this->hasMany(User::class);
    }
}
