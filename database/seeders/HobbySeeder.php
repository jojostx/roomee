<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HobbySeeder extends Seeder
{
    private $hobbies = [
        [
            'name' => 'dancing',
        ],
        [
            'name' => 'singing',
        ],
        [
            'name' => 'reading',
        ],
        [
            'name' => 'cooking',
        ],
        [
            'name' => 'watching movies',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect($this->hobbies)->each(function ($hobby) {
            DB::table('hobbies')->insert(
                array_merge($hobby, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        });
    }
}
