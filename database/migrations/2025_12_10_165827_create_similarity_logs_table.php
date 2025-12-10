<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('similarity_logs', function (Blueprint $table) {
            $table->id();
            $table->text('input_prompt');
            $table->foreignId('matched_prompt_id')->nullable()->constrained('prompts')->onDelete('set null');
            $table->decimal('similarity_score', 5, 4)->nullable();
            $table->boolean('found_match')->default(false);
            $table->json('top_matches')->nullable(); // Store top 3 matches
            $table->timestamps();

            $table->index('found_match');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('similarity_logs');
    }
};
