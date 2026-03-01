<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('identity_document_path')->nullable()->after('avatar');
            $table->string('selfie_path')->nullable()->after('identity_document_path');
            $table->string('verification_status')->default('unverified')->after('selfie_path');
            $table->text('rejection_reason')->nullable()->after('verification_status');
            $table->timestamp('verification_submitted_at')->nullable()->after('rejection_reason');

            $table->boolean('is_suspended')->default(false)->after('verification_submitted_at');
            $table->timestamp('suspended_at')->nullable()->after('is_suspended');
            $table->text('suspension_reason')->nullable()->after('suspended_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'identity_document_path',
                'selfie_path',
                'verification_status',
                'rejection_reason',
                'verification_submitted_at',
                'is_suspended',
                'suspended_at',
                'suspension_reason',
            ]);
        });
    }
};

