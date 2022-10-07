<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TownSeeder extends Seeder
{
    private $towns = [
        'choba',
        'alakahia',
        'aluu',
        'rumuosi',
        'abraka',
        'mfam',
        'garki',
        'itakpe',
        'gwagwalada',
        'lugbe',
        'bwari',
        'kuje'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('towns')->insert(
            collect($this->towns)->map(function ($town) {
                return [
                    'uuid' => str()->uuid()->toString(),
                    'name' => $town,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray()
        );
    }
}
