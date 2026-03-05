<?php

namespace Tests\Feature\Similarity;

use App\Services\Similarity;
use Tests\TestCase;

class SimilarityServiceTest extends TestCase
{
    public function test_ovrs_returns_perfect_score_for_identical_ranges(): void
    {
        $score = Similarity::OVRS([40000, 120000], [40000, 120000]);

        $this->assertSame(1.0, $score);
    }

    public function test_ovrs_returns_zero_for_non_overlapping_ranges(): void
    {
        $score = Similarity::OVRS([40000, 80000], [120000, 160000]);

        $this->assertSame(0.0, $score);
    }

    public function test_ovrs_matches_expected_partial_overlap_score(): void
    {
        $score = Similarity::OVRS([40000, 120000], [80000, 160000]);

        $this->assertSame(0.6, $score);
    }

    public function test_ovrs_keeps_step_alignment_behavior(): void
    {
        $score = Similarity::OVRS([50000, 90000], [60000, 100000]);

        $this->assertSame(0.0, $score);
    }
}

