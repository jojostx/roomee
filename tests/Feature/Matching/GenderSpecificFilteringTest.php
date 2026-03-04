<?php

namespace Tests\Feature\Matching;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenderSpecificFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_strict_gender_filter_enabled_user_only_sees_same_gender(): void
    {
        $school = $this->createSchool();

        $viewer = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'settings' => [],
        ]);

        $sameGenderCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'settings' => [],
        ]);

        User::factory()->create([
            'gender' => 'female',
            'school_id' => $school->getKey(),
            'settings' => [],
        ]);

        User::factory()->create([
            'gender' => 'female',
            'school_id' => $school->getKey(),
            'settings' => [
                'matching' => [
                    'strict_gender_filter' => false,
                ],
            ],
        ]);

        $matchedIds = $viewer->validUsers()->pluck('id')->all();

        $this->assertEquals([$sameGenderCandidate->getKey()], $matchedIds);
    }

    public function test_strict_gender_filter_disabled_user_can_see_cross_gender_users_who_opt_out(): void
    {
        $school = $this->createSchool();

        $viewer = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'settings' => [
                'matching' => [
                    'strict_gender_filter' => false,
                ],
            ],
        ]);

        $sameGenderCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'settings' => [],
        ]);

        $femaleOptedOutCandidate = User::factory()->create([
            'gender' => 'female',
            'school_id' => $school->getKey(),
            'settings' => [
                'matching' => [
                    'strict_gender_filter' => false,
                ],
            ],
        ]);

        $femaleStrictCandidate = User::factory()->create([
            'gender' => 'female',
            'school_id' => $school->getKey(),
            'settings' => [],
        ]);

        $matchedIds = $viewer->validUsers()->pluck('id')->all();

        $this->assertContains($sameGenderCandidate->getKey(), $matchedIds);
        $this->assertContains($femaleOptedOutCandidate->getKey(), $matchedIds);
        $this->assertNotContains($femaleStrictCandidate->getKey(), $matchedIds);
    }

    protected function createSchool(): School
    {
        return School::query()->create([
            'name' => 'Test University',
            'short_name' => 'TU',
            'state' => 'Lagos',
        ]);
    }
}
