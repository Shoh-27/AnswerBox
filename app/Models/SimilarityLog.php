<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimilarityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'input_prompt',
        'matched_prompt_id',
        'similarity_score',
        'found_match',
        'top_matches',
    ];

    protected $casts = [
        'similarity_score' => 'float',
        'found_match' => 'boolean',
        'top_matches' => 'array',
    ];

    /**
     * Get the matched prompt
     */
    public function matchedPrompt()
    {
        return $this->belongsTo(Prompt::class, 'matched_prompt_id');
    }
}
