<?php

namespace App\Http\ModelSimilarity;

use App\Models\User;
use App\Services\Similarity;
use Illuminate\Support\Collection;

class UserSimilarity
{
    protected $user;
    protected $users;
    protected $hobbyWeight = 0.8;
    protected $dislikeWeight = 1.2;
    protected $budgetWeight = 1.2;
    protected $roomsWeight = 1;
    protected $courseLevelWeight = 0.8;
    protected $propertyLocationWeight = 1;

    public function __construct(User $user = null)
    {
        $this->user = $user ?? auth()->user();
    }

    public function make(User $user)
    {
        return new static($user);
    }
  
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }

    public function calculateSimilarityScore(User $user_1, User $user_2): float
    {
        $user_1 = $user_1 ?? auth()->user();

        $user_1_dislikes = $user_1->dislikes->pluck('name')->toArray();
        $user_2_dislikes = $user_2->dislikes->pluck('name')->toArray();
        
        // $user_1_dislikes = $user_1->dislikes()->select('name')->get()->pluck('name')->toArray();
        // $user_2_dislikes = $user_2->dislikes()->select('name')->get()->pluck('name')->toArray();

        $user_1_hobbies = $user_1->hobbies->pluck('name')->toArray();
        $user_2_hobbies = $user_2->hobbies->pluck('name')->toArray();
        $user_1_property_locations = $user_1->towns->pluck('name')->toArray();
        $user_2_property_locations = $user_2->towns->pluck('name')->toArray();
        $user_1_budget = [$user_1->min_budget, $user_1->max_budget];
        $user_2_budget = [$user_2->min_budget, $user_2->max_budget];

        return array_sum([
            Similarity::OVRS($user_1_budget, $user_2_budget) * $this->budgetWeight,
            Similarity::OVRS_kernel($user_1_hobbies, $user_2_hobbies) * $this->hobbyWeight,
            Similarity::OVRS_kernel($user_1_dislikes, $user_2_dislikes) * $this->dislikeWeight,
            Similarity::OVRS_kernel($user_1_property_locations, $user_2_property_locations) * $this->propertyLocationWeight,
            Similarity::simple_Diff_Sim($user_1->course_level, $user_2->course_level) * $this->courseLevelWeight,
            Similarity::simple_Diff_Sim($user_1->rooms, $user_2->rooms) * $this->roomsWeight,
        ]) / ($this->budgetWeight + $this->hobbyWeight + $this->dislikeWeight + $this->propertyLocationWeight + $this->courseLevelWeight + $this->roomsWeight);
    }

    public function calculateUsersSimilarityScore(?Collection $users)
    {
        return $users->each(function ($user) {
            $user->similarity_score =  round($this->calculateSimilarityScore($this->user, $user), 2) * 100;
        });
    }
  
    public function calculateUserSimilarityScore(User $user): float
    {
        return round($this->calculateSimilarityScore($this->user, $user), 2) * 100;
    }
}