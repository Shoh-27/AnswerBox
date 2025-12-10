<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'normalized_question',
        'is_favorite',
        'usage_count',
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Get all answers for this prompt
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the primary answer
     */
    public function primaryAnswer()
    {
        return $this->hasOne(Answer::class)->where('is_primary', true);
    }

    /**
     * Get similarity logs
     */
    public function similarityLogs()
    {
        return $this->hasMany(SimilarityLog::class, 'matched_prompt_id');
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Normalize question for matching
     */
    public static function normalizeText($text)
    {
        return mb_strtolower(trim($text));
    }

    /**
     * Boot method to auto-normalize
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prompt) {
            $prompt->normalized_question = self::normalizeText($prompt->question);
        });

        static::updating(function ($prompt) {
            if ($prompt->isDirty('question')) {
                $prompt->normalized_question = self::normalizeText($prompt->question);
            }
        });
    }
}
