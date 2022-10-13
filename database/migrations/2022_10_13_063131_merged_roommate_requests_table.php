<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMergedRelations\Facades\Schema;
use Staudenmeir\LaravelMigrationViews\Facades\Schema as MigrationSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
                    ->recievedRoommateRequests()
                    ->select(
                        'roommate_requests.created_at as pivot_created_at',
                        'roommate_requests.sender_id as sender_id',
                        'roommate_requests.recipient_id as recipient_id'
                    )
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        MigrationSchema::dropViewIfExists('merged_roommate_requests_view');
    }
};
