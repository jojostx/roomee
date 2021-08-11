<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TownSeeder extends Seeder
{
    private $towns = [
        'uniport' => ['choba', 'alakahia', 'aluu', 'rumuosi'],
        'delsu' => ['abraka', 'mafam', 'garki', 'itakpe'],
        'uniabj' => ['gwagwalada', 'lugbe', 'bwari', 'kuje'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //refactor to collections
        foreach ($this->towns as $school => $areas) {
            $school_id = School::firstWhere('short_name', strtoupper($school))->id;

            foreach ($areas as $area) {
                DB::table('towns')->insert([
                    'school_id' => $school_id,
                    'name' => $area,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
