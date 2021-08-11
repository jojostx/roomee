<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\School;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UniversitySeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(CourseSchoolSeeder::class);
        $this->call(DislikeSeeder::class);     
        $this->call(HobbySeeder::class);     
        $this->call(TownSeeder::class);     
    }
}