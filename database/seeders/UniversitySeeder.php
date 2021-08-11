<?php

namespace Database\Seeders;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{

    public $universities = [
        [
            'name' => 'University of Abuja',
            'short_name' => 'UNIABJ',
            'state' => 'Abuja'
        ],
        [
            'name' => 'University of Ibadan',
            'short_name' => 'UI',
            'state' => 'Oyo'
        ],
        [
            'name' => 'Obafemi Awolowo University',
            'short_name' => 'OAU',
            'state' => 'Osun'
        ],
        [
            'name' => 'Abia State University',
            'short_name' => 'ABSU',
            'state' => 'Abia'
        ],
        [
            'name' => 'Delta State University',
            'short_name' => 'DELSU',
            'state' => 'Delta'
        ],
        [
            'name' => 'University of Port-Harcourt',
            'short_name' => 'UNIPORT',
            'state' => 'Rivers'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->universities as $university) {

            $university = array_merge($university, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('schools')->insert($university);
        }
    }
}
