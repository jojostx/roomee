<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Town;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolTownSeeder extends Seeder
{
  private $school_towns = [
    'uniport' => ['choba', 'alakahia', 'aluu', 'rumuosi'],
    'delsu' => ['abraka', 'mfam', 'garki', 'itakpe'],
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
    //retrieve all the towns as [id => name] from the db
    $schools = School::all()->pluck('short_name', 'id');
    $towns = Town::all()->pluck('name', 'id');

    collect($this->school_towns)->each(function ($_towns, $school) use ($schools, $towns) {
      //search and retrieve the id of the school from the collection
      $school_id = $schools->search(strtoupper($school));

      //if the school is not in the collection return immediately
      if (!$school_id) {
        return;
      }

      //else perform the saving of towns to db with foreign id of school id
      $timestamp = now();

      DB::table('school_town')->insert(
        collect($_towns)
          ->filter(fn ($town) => $towns->search($town))
          ->map(function ($town) use ($school_id, $towns, $timestamp) {
            if ($town_id = $towns->search($town)) {
              return [
                'school_id' => $school_id,
                'town_id' => $town_id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
              ];
            }
          })
          ->toArray()
      );
    });
  }
}
