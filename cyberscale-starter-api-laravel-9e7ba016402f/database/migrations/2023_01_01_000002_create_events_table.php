<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->text('address')->nullable();
            $table->integer('capacity')->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->enum('category', ['music', 'sports', 'art', 'tech', 'business'])->default('music'); // Enum for categories
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
