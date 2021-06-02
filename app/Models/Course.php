<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;


    /**
     * The schools that belong to this course (the schools that offers this course).
     */
    public function schools(){
        return  $this->belongsToMany(School::class, 'course_school')->withTimestamps();
    }

    /**
     * The users that belong to this course (the users that offer this course).
     */
    public function users(){
        return  $this->hasMany(User::class);
    }
}
