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
        DB::table('dislikes')->insert(
            collect($this->dislikes)->map(function ($dislike) {
                return array_merge($dislike, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })->toArray()
        );
    }
}
