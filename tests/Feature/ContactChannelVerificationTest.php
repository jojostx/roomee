<?php

namespace Tests\Feature;

use App\Enums\ContactChannelType;
use App\Models\ContactChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactChannelVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_verified_and_enabled_contact_channels_are_returned(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $verifiedChannel = ContactChannel::create([
            'uuid' => str()->uuid()->toString(),
            'user_id' => $user->id,
            'type' => ContactChannelType::WHATSAPP->value,
            'link' => 'https://wa.me/1234567890',
            'is_enabled' => true,
        ]);
        $verifiedChannel->markAsVerified();

        ContactChannel::create([
            'uuid' => str()->uuid()->toString(),
            'user_id' => $user->id,
            'type' => ContactChannelType::TWITTER->value,
            'link' => 'https://twitter.com/example',
            'is_enabled' => true,
        ]);

        ContactChannel::create([
            'uuid' => str()->uuid()->toString(),
            'user_id' => $user->id,
            'type' => ContactChannelType::FACEBOOK->value,
            'link' => 'https://facebook.com/example',
            'is_enabled' => false,
            'verified_at' => now(),
        ]);

        $channels = $user->getVerifiedContactChannels();

        $this->assertTrue(
            $channels->contains(fn (ContactChannel $channel) => $channel->type === ContactChannelType::EMAIL->value)
        );
        $this->assertTrue(
            $channels->contains(fn (ContactChannel $channel) => $channel->type === ContactChannelType::WHATSAPP->value)
        );
        $this->assertFalse(
            $channels->contains(fn (ContactChannel $channel) => $channel->type === ContactChannelType::TWITTER->value)
        );
        $this->assertFalse(
            $channels->contains(fn (ContactChannel $channel) => $channel->type === ContactChannelType::FACEBOOK->value)
        );

        $this->assertNotNull($verifiedChannel->fresh()->verified_at);
    }
}
