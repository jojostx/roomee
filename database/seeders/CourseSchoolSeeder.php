<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\School;
use Illuminate\Database\Seeder;

class CourseSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::all();
        
        School::all()->each(function($school) use ($courses) {
           $school->courses()->attach($courses->random(3));
        });
    }
}
