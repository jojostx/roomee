<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'rent_amount' => $this->faker->numberBetween(80000, 600000),
            'rent_period' => $this->faker->randomElement([
                Listing::RENT_PERIOD_MONTHLY,
                Listing::RENT_PERIOD_ANNUALLY,
            ]),
            'move_in_date' => now()->addWeeks(rand(1, 8))->toDateString(),
            'amenities' => [array_rand(Listing::AMENITY_OPTIONS)],
            'house_rules' => [array_rand(Listing::HOUSE_RULE_OPTIONS)],
            'images' => [],
            'is_published' => false,
        ];
    }
}

