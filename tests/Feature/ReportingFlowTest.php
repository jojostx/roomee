<?php

namespace Tests\Feature;

use App\Http\Livewire\Traits\CanRetrieveUser;
use App\Http\Livewire\Traits\WithReporting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_use_target_user_from_arguments(): void
    {
        $reporter = User::factory()->create(['gender' => 'male']);
        $reportee = User::factory()->create(['gender' => 'male']);
        $decoy = User::factory()->create(['gender' => 'male']);

        $reportId = DB::table('reports')->insertGetId([
            'uuid' => str()->uuid()->toString(),
            'description' => 'Spam',
            'severity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $harness = new ReportingHarness($reporter);
        $harness->user = $decoy;
        $harness->report($reportee, [$reportId]);

        $this->assertDatabaseHas('report_user', [
            'reporter_id' => $reporter->id,
            'reportee_id' => $reportee->id,
            'report_id' => $reportId,
        ]);

        $this->assertDatabaseMissing('report_user', [
            'reporter_id' => $reporter->id,
            'reportee_id' => $decoy->id,
            'report_id' => $reportId,
        ]);
    }
}

class ReportingHarness
{
    use WithReporting;
    use CanRetrieveUser;

    public ?User $user = null;

    public function __construct(private User $authUser)
    {
    }

    protected function getAuthModel(): ?User
    {
        return $this->authUser;
    }

    public function report(User $user, array $reportIds): void
    {
        $this->reportUser($user, $reportIds);
    }
}
