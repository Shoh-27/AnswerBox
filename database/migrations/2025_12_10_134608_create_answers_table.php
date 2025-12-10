<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prompt_id')->constrained()->onDelete('cascade');
            $table->text('answer');
            $table->boolean('is_primary')->default(true);
            $table->integer('helpfulness_score')->default(0);
            $table->timestamps();

            $table->index('prompt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
