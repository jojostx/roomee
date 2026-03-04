<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->unsignedBigInteger('rent_amount');
            $table->enum('rent_period', ['monthly', 'annually'])->default('monthly');
            $table->date('move_in_date');
            $table->json('amenities')->nullable();
            $table->json('house_rules')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'move_in_date']);
            $table->index(['city', 'rent_amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};

