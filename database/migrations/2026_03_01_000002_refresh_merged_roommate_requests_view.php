<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMergedRelations\Facades\Schema;
use Staudenmeir\LaravelMigrationViews\Facades\Schema as MigrationSchema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createOrReplaceMergeViewWithoutDuplicates(
            'merged_roommate_requests_view',
            [
                (new User())
                    ->sentRoommateRequests()
                    ->select(
                        'roommate_requests.created_at as pivot_created_at',
                        'roommate_requests.sender_id as sender_id',
                        'roommate_requests.recipient_id as recipient_id'
                    ),

                (new User())
                    ->receivedRoommateRequests()
                    ->select(
                        'roommate_requests.created_at as pivot_created_at',
                        'roommate_requests.sender_id as sender_id',
                        'roommate_requests.recipient_id as recipient_id'
                    ),
            ]
        );
    }

    public function down(): void
    {
        MigrationSchema::dropViewIfExists('merged_roommate_requests_view');
    }
};
