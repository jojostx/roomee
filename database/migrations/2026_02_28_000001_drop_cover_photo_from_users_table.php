<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'cover_photo')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('cover_photo');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'cover_photo')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('cover_photo')->nullable();
        });
    }
};
