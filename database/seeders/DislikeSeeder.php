<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DislikeSeeder extends Seeder
{
    private $dislikes = [
        [
            'name' => 'smoking',
        ],
        [
            'name' => 'stealing',
        ],
        [
            'name' => 'violence',
        ],
        [
            'name' => 'loud music',
        ],
        [
            'name' => 'dirtiness',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect($this->dislikes)->each(function ($dislike) {
            DB::table('dislikes')->insert(
                array_merge($dislike, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        });
    }
}
