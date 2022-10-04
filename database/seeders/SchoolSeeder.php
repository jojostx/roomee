<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolSeeder extends Seeder
{
    public $schools = [
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
        DB::table('schools')->insert(
            collect($this->schools)->map(function ($school) {
                return array_merge($school, [
                    'uuid' => Str::uuid()->toString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })->toArray()
        );
    }
}
