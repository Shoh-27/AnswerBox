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
        Schema::create('prompts', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('normalized_question'); // Lowercase, trimmed for matching
            $table->boolean('is_favorite')->default(false);
            $table->integer('usage_count')->default(0);
            $table->timestamps();

            $table->index('normalized_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};
