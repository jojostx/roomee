<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'uuid' => str()->uuid()->toString(),
                'first_name' => 'Tucker',
                'last_name' => 'Lawrence',
                'gender' => 'male',
                'email' => 'Tucker.lawrence@yahoo.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'remember_token' => str()->random(10),
                'profile_updated' => false,
                'bio'=> 'Sometimes I can be quite overbearing when it comes to hygiene, so i would love my roommate to be a very organized person' ,
                'created_at' => now(),
                'updated_at' => now(),
                'avatar' => '1628751944-iuoKwaPW.png',
            ],
            [
                'uuid' => str()->uuid()->toString(),
                'first_name' => 'Mark',
                'last_name' => 'Ivan',
                'gender' => 'male',
                'email' => 'Marka.ivan@yahoo.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'remember_token' => str()->random(10),
                'profile_updated' => false,
                'bio'=> 'I am very devoted christian and therefore I want a roommate who is preferably a christian',
                'created_at' => now(),
                'updated_at' => now(),
                'avatar' => null,
            ],
        ];

        DB::table('users')->insertOrIgnore($users);

        $this->seedTestChatUsers();
    }

    /**
     * Create two fully-verified test users with an accepted roommate request
     * and a pre-existing chat room so the chat feature can be tested immediately.
     *
     * Credentials:
     *   alice@roomee.test / password
     *   bob@roomee.test   / password
     */
    private function seedTestChatUsers(): void
    {
        $school = DB::table('schools')->first();
        $course = DB::table('courses')->first();
        $townIds = DB::table('towns')->limit(2)->pluck('id');
        $hobbyIds = DB::table('hobbies')->limit(2)->pluck('id');
        $dislikeIds = DB::table('dislikes')->limit(2)->pluck('id');

        if (!$school || !$course || $townIds->isEmpty()) {
            $this->command->warn('Skipping test chat users: reference data (schools/courses/towns) not yet seeded.');
            return;
        }

        $alice = User::updateOrCreate(
            ['email' => 'alice@roomee.test'],
            [
                'uuid'                => str()->uuid()->toString(),
                'first_name'          => 'Alice',
                'last_name'           => 'Test',
                'gender'              => 'female',
                'password'            => bcrypt('password'),
                'email_verified_at'   => now(),
                'profile_updated'     => true,
                'verification_status' => VerificationStatus::APPROVED,
                'role'                => UserRole::USER,
                'bio'                 => 'Test user Alice. Loves clean shared spaces.',
                'rooms'               => 1,
                'min_budget'          => 30000,
                'max_budget'          => 80000,
                'course_level'        => 3,
                'school_id'           => $school->id,
                'course_id'           => $course->id,
                'is_suspended'        => false,
                'settings'            => json_encode(['matching' => ['strict_gender_filter' => false]]),
            ]
        );

        $alice->towns()->sync($townIds);
        $alice->hobbies()->sync($hobbyIds);
        $alice->dislikes()->sync($dislikeIds);

        $bob = User::updateOrCreate(
            ['email' => 'bob@roomee.test'],
            [
                'uuid'                => str()->uuid()->toString(),
                'first_name'          => 'Bob',
                'last_name'           => 'Test',
                'gender'              => 'male',
                'password'            => bcrypt('password'),
                'email_verified_at'   => now(),
                'profile_updated'     => true,
                'verification_status' => VerificationStatus::APPROVED,
                'role'                => UserRole::USER,
                'bio'                 => 'Test user Bob. Easy-going and tidy.',
                'rooms'               => 2,
                'min_budget'          => 25000,
                'max_budget'          => 70000,
                'course_level'        => 2,
                'school_id'           => $school->id,
                'course_id'           => $course->id,
                'is_suspended'        => false,
                'settings'            => json_encode(['matching' => ['strict_gender_filter' => false]]),
            ]
        );

        $bob->towns()->sync($townIds);
        $bob->hobbies()->sync($hobbyIds);
        $bob->dislikes()->sync($dislikeIds);

        // Create the accepted roommate request and chat room.
        DB::table('roommate_requests')->updateOrInsert(
            ['id' => \App\Models\RoommateRequest::getCompositeKeyFromIds($alice->id, $bob->id)],
            [
                'uuid'         => str()->uuid()->toString(),
                'sender_id'    => $alice->id,
                'recipient_id' => $bob->id,
                'status'       => \App\Enums\RoommateRequestStatus::ACCEPTED->value,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        );

        ChatRoom::firstOrCreateBetween($alice, $bob);

        $this->command->info('Test chat users seeded:');
        $this->command->line('  alice@roomee.test / password');
        $this->command->line('  bob@roomee.test   / password');
    }
}
