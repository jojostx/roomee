<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = collect($this->getFaqCategories())
            ->transform(function ($category) {
                return array_merge($category, [
                    'uuid' => Str::uuid()->toString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        DB::table('faq_categories')->insert($data->toArray());
    }

    public function getFaqCategories()
    {
        return [
            [
                'id' => 1,
                'title' => "General",
            ],
            [
                'id' => 2,
                'title' => "Roommate Recommendations",
            ],
            [
                'id' => 3,
                'title' => "Messaging",
            ],
            [
                'id' => 4,
                'title' => "Roomee's policies and reporting",
            ],
            [
                'id' => 5,
                'title' => "Profile and Account",
            ],
        ];
    }
}
