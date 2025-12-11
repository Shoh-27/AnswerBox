<?php

namespace App\Services;

use App\Models\Prompt;
use App\Models\Setting;

class SimilarityService
{
    private $threshold;
    private $maxResults;
    private $levWeight;
    private $simTextWeight;

    public function __construct()
    {
        $this->threshold = Setting::getValue('similarity_threshold', 0.45);
        $this->maxResults = Setting::getValue('max_results', 3);
        $this->levWeight = Setting::getValue('algorithm_weight_levenshtein', 0.5);
        $this->simTextWeight = Setting::getValue('algorithm_weight_similar_text', 0.5);
    }

    /**
     * Compute Levenshtein similarity (normalized 0-1)
     */
    public function computeLevenshtein($a, $b)
    {
        $a = mb_strtolower(trim($a));
        $b = mb_strtolower(trim($b));

        $maxLen = max(mb_strlen($a), mb_strlen($b));

        if ($maxLen == 0) {
            return 1.0;
        }

        $distance = levenshtein($a, $b);
        $similarity = 1 - ($distance / $maxLen);

        return max(0, min(1, $similarity));
    }

    /**
     * Compute similar_text percentage (0-1)
     */
    public function computeSimilarText($a, $b)
    {
        $a = mb_strtolower(trim($a));
        $b = mb_strtolower(trim($b));

        $percent = 0;
        similar_text($a, $b, $percent);

        return $percent / 100;
    }


}
