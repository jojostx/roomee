<?php

namespace Tests\Feature\Matching;

use App\Models\Blocklist;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimilarityBudgetPrefilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_similarity_budget_prefilter_keeps_only_overlapping_valid_candidates(): void
    {
        $school = School::query()->create([
            'name' => 'First School',
            'short_name' => 'FS',
            'state' => 'Lagos',
        ]);

        $otherSchool = School::query()->create([
            'name' => 'Second School',
            'short_name' => 'SS',
            'state' => 'Abuja',
        ]);

        $viewer = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 80000,
            'max_budget' => 160000,
            'settings' => [],
        ]);

        $overlappingCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 100000,
            'max_budget' => 180000,
            'settings' => [],
        ]);

        $nonOverlappingCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 200000,
            'max_budget' => 260000,
            'settings' => [],
        ]);

        $missingBudgetCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => null,
            'max_budget' => null,
            'settings' => [],
        ]);

        $differentSchoolCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $otherSchool->getKey(),
            'min_budget' => 100000,
            'max_budget' => 180000,
            'settings' => [],
        ]);

        $blockedCandidate = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 100000,
            'max_budget' => 180000,
            'settings' => [],
        ]);

        Blocklist::query()->create([
            'blocker_id' => $viewer->getKey(),
            'blockee_id' => $blockedCandidate->getKey(),
        ]);

        $candidateIds = $viewer
            ->validNonBlockingUsers()
            ->forSimilarityBudgetOverlap($viewer)
            ->pluck('id')
            ->all();

        $this->assertContains($overlappingCandidate->getKey(), $candidateIds);
        $this->assertNotContains($nonOverlappingCandidate->getKey(), $candidateIds);
        $this->assertNotContains($missingBudgetCandidate->getKey(), $candidateIds);
        $this->assertNotContains($differentSchoolCandidate->getKey(), $candidateIds);
        $this->assertNotContains($blockedCandidate->getKey(), $candidateIds);
        $this->assertCount(1, $candidateIds);
    }
}

