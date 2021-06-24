<?php

namespace Database\Seeders;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert(
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
                'name' => 'Delta State University',
                'short_name' => 'DELSU',
                'state' => 'Delta'
            ],
            
        );
    }
}
