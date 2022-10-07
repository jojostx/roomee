<?php

namespace App\Http\ModelSimilarity;

use App\Models\User;
use App\Services\Similarity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

trait canCalculateUserSimilarity
{
    protected $hobbyWeight = 0.8;
    protected $dislikeWeight = 1;
    protected $budgetWeight = 1.4;
    protected $roomsWeight = 1;
    protected $courseLevelWeight = 0.8;
    protected $propertyLocationWeight = 1;

    public function calculateSimilarityScore(User $user_2): float
    {
        $user_1_dislikes = $this->dislikes->pluck('name')->toArray();
        $user_2_dislikes = $user_2->dislikes->pluck('name')->toArray();

        $user_1_hobbies = $this->hobbies->pluck('name')->toArray();
        $user_2_hobbies = $user_2->hobbies->pluck('name')->toArray();

        $user_1_property_locations = $this->towns->pluck('name')->toArray();
        $user_2_property_locations = $user_2->towns->pluck('name')->toArray();

        $user_1_budget = [$this->min_budget, $this->max_budget];
        $user_2_budget = [$user_2->min_budget, $user_2->max_budget];

        return array_sum([
            Similarity::OVRS($user_1_budget, $user_2_budget) * $this->budgetWeight,
            Similarity::OVRS_kernel($user_1_hobbies, $user_2_hobbies) * $this->hobbyWeight,
            Similarity::OVRS_kernel($user_1_dislikes, $user_2_dislikes) * $this->dislikeWeight,
            Similarity::OVRS_kernel($user_1_property_locations, $user_2_property_locations) * $this->propertyLocationWeight,
            Similarity::simple_Diff_Sim($this->course_level, $user_2->course_level) * $this->courseLevelWeight,
            Similarity::simple_Diff_Sim($this->rooms, $user_2->rooms) * $this->roomsWeight,
        ]) / ($this->budgetWeight + $this->hobbyWeight + $this->dislikeWeight + $this->propertyLocationWeight + $this->courseLevelWeight + $this->roomsWeight);
    }

    public function calculateUsersSimilarityScore(?Collection $users): Collection
    {
        return $users->each(function ($user) {
            $user->similarity_score = round($this->calculateSimilarityScore($user), 2) * 100;
        });
    }

    public function calculateUserSimilarityScore(?User $user): int
    {
        return filled($user)? intval($this->calculateSimilarityScore($user) * 100) : 0;
    }
}
