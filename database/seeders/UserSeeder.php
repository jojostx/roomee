<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $users = [
            [
                'uuid' => Str::uuid()->toString(),
                'firstname' => 'Tucker',
                'lastname' => 'Lawrence',
                'gender' => 'male',
                'email' => 'Tucker.lawrence@yahoo.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'remember_token' => Str::random(10),
                'profile_updated' => false,
                'bio'=> 'Sometimes I can be quite overbearing when it comes to hygiene, so i would love my roommate to be a very organized person' ,
                'created_at' => now(),
                'updated_at' => now(),
                'avatar' => '1628751944-iuoKwaPW.png',
                'cover_photo' => '1628751944-vzT2iZ8J.png',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'firstname' => 'Mark',
                'lastname' => 'Ivan',
                'gender' => 'male',
                'email' => 'Mark.ivan@yahoo.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'remember_token' => Str::random(10),
                'profile_updated' => false,
                'bio'=> 'I am very devoted christian and therefore I want a roommate who is preferably a christian',
                'created_at' => now(),
                'updated_at' => now(),
                'avatar' => asset('images/avatar_placeholder.png'),
                'cover_photo' => asset('images/cover_placeholder.png'),
            ],
        ];

        DB::table('users')->insert($users);
    }

}
