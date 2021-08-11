<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public $courses = [
        [
            'name' => 'Petroleum Engineering',
            'short_name' => 'Pet.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Gas Engineering',
            'short_name' => 'Gas.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Civil Engineering',
            'short_name' => 'Civ.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Computer Engineering',
            'short_name' => 'Comp.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Mechanical Engineering',
            'short_name' => 'Mech.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Chemical Engineering',
            'short_name' => 'Chem.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Mechatronics Engineering',
            'short_name' => 'Met.Eng',
            'max_level' => 500,
        ],
        [
            'name' => 'Electrical Engineering',
            'short_name' => 'Elect.Eng',
            'max_level' => 500,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->courses as $course) {

            $course = array_merge($course, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('courses')->insert($course);
        }
    }
}
