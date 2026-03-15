<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_room_id' => ChatRoom::factory(),
            'sender_id' => User::factory(),
            'message' => fake()->sentence(),
            'read_at' => null,
        ];
    }
}
