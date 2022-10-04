<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public $courses = [
        [
            'name' => 'Petroleum Engineering',
            'short_name' => 'Pet.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Gas Engineering',
            'short_name' => 'Gas.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Civil Engineering',
            'short_name' => 'Civ.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Computer Engineering',
            'short_name' => 'Comp.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Mechanical Engineering',
            'short_name' => 'Mech.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Chemical Engineering',
            'short_name' => 'Chem.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Mechatronics Engineering',
            'short_name' => 'Met.Eng',
            'max_level' => 600,
        ],
        [
            'name' => 'Electrical Engineering',
            'short_name' => 'Elect.Eng',
            'max_level' => 600,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert(
            collect($this->courses)->map(function ($course) {
                return array_merge($course, [
                    'uuid' => Str::uuid()->__toString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })->toArray()
        );
    }
}
