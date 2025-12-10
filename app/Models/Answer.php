<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt_id',
        'answer',
        'is_primary',
        'helpfulness_score',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'helpfulness_score' => 'integer',
    ];

    /**
     * Get the prompt this answer belongs to
     */
    public function prompt()
    {
        return $this->belongsTo(Prompt::class);
    }

    /**
     * Vote helpful
     */
    public function voteHelpful()
    {
        $this->increment('helpfulness_score');
    }

    /**
     * Vote unhelpful
     */
    public function voteUnhelpful()
    {
        $this->decrement('helpfulness_score');
    }
}
