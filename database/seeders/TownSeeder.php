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
        //retrieve all the schools as [id => short_name] from the db
        $schools = School::all()->pluck('short_name', 'id'); 

        collect($this->towns)->each(function ($areas, $school) use ($schools)
        {
            //search and retrieve the id of the school from the collection
            $school_id = $schools->search(strtoupper($school));

            //if the school is not in the collection return immediately
            if (!$school_id) {
                return;
            }
            
            //else perform the saving of towns to db with foreign id of school id
            $timestamp = now();
           
            DB::table('towns')->insert(
                collect($areas)->map(function ($area) use ($school_id, $timestamp) {
                    return [
                        'school_id' => $school_id,
                        'name' => $area,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                })->toArray()
            );
        });
    }
}
