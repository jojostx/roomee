<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    private $reports = [
        [
            'desc' => 'profile contains sexual content',
            'severity' => 10
        ],
        [
            'desc' => 'profile contains advertisement',
            'severity' => 9
        ],
        [
            'desc' => 'profile contains explicit content',
            'severity' => 8
        ],
        [
            'desc' => 'profile is an impersonation',
            'severity' => 9
        ],
        [
            'desc' => 'profile is fraudulent',
            'severity' => 9
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reports')->insert(
            collect($this->reports)->map(function ($report) {
                return array_merge($report, [
                    'uuid' => Str::uuid()->toString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            })->toArray()
        );
    }
}
