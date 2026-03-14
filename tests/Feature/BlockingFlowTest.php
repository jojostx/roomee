<?php

namespace Tests\Feature;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithBlocking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_block_and_unblock_another_user(): void
    {
        $blocker = User::factory()->create(['gender' => 'male']);
        $blockee = User::factory()->create(['gender' => 'male']);

        $this->assertTrue($blocker->block($blockee));
        $this->assertDatabaseHas('blocklists', [
            'blocker_id' => $blocker->id,
            'blockee_id' => $blockee->id,
        ]);

        $this->assertTrue($blocker->unblock($blockee));
        $this->assertDatabaseMissing('blocklists', [
            'blocker_id' => $blocker->id,
            'blockee_id' => $blockee->id,
        ]);
    }

    public function test_blocklist_entry_can_be_detected_for_removal(): void
    {
        $blocker = User::factory()->create(['gender' => 'male']);
        $blockee = User::factory()->create(['gender' => 'male']);

        $blocker->block($blockee);

        $harness = new BlockingHarness($blocker);

        $this->assertTrue($harness->canRemove($blockee));
    }
}

class BlockingHarness
{
    use WithBlocking;
    use CanRetrieveUser;

    public function __construct(private User $authUser)
    {
    }

    protected function getAuthModel(): ?User
    {
        return $this->authUser;
    }

    public function canRemove(User $user): bool
    {
        return $this->canBeRemovedFromBlocklist($user);
    }
}
