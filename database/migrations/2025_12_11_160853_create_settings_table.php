<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, float, boolean
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            [
                'key' => 'similarity_threshold',
                'value' => '0.45',
                'type' => 'float',
                'description' => 'Minimum similarity score to consider a match',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_results',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Maximum number of results to return',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'algorithm_weight_levenshtein',
                'value' => '0.5',
                'type' => 'float',
                'description' => 'Weight for Levenshtein algorithm (0-1)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'algorithm_weight_similar_text',
                'value' => '0.5',
                'type' => 'float',
                'description' => 'Weight for similar_text algorithm (0-1)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
