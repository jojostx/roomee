<?php

namespace Tests\Feature;

use App\Enums\RoommateRequestStatus;
use App\Http\Livewire\Traits\CanRetrieveUser;
use App\Http\Livewire\Traits\WithRequesting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoommateRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_roommate_request_can_be_denied_via_requesting_trait(): void
    {
        $sender = User::factory()->create(['gender' => 'male']);
        $recipient = User::factory()->create(['gender' => 'male']);

        $this->assertTrue($sender->sendRoommateRequest($recipient));

        $harness = new RoommateRequestingHarness($recipient);
        $harness->deny($sender);

        $this->assertDatabaseHas('roommate_requests', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => RoommateRequestStatus::DENIED->value,
        ]);
    }
}

class RoommateRequestingHarness
{
    use WithRequesting;
    use CanRetrieveUser;

    public function __construct(private User $authUser)
    {
    }

    protected function getAuthModel(): ?User
    {
        return $this->authUser;
    }

    public function deny(User $user): void
    {
        $this->denyRoommateRequest($user);
    }
}
