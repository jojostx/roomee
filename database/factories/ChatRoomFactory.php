<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatRoom>
 */
class ChatRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_shared_by_a' => false,
            'contact_shared_by_b' => false,
        ];
    }

    public function withBothContactsShared(): static
    {
        return $this->state([
            'contact_shared_by_a' => true,
            'contact_shared_by_b' => true,
        ]);
    }
}
