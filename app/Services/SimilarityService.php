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

    /**
     * Combined score (weighted average)
     */
    public function combinedScore($a, $b)
    {
        $levScore = $this->computeLevenshtein($a, $b);
        $simTextScore = $this->computeSimilarText($a, $b);

        // Weighted average
        $combined = ($levScore * $this->levWeight) + ($simTextScore * $this->simTextWeight);

        // Normalize (since weights should sum to 1)
        $totalWeight = $this->levWeight + $this->simTextWeight;
        if ($totalWeight > 0) {
            $combined = $combined / $totalWeight;
        }

        return round($combined, 4);
    }

    /**
     * Find best matches for input prompt
     * Returns array of matches with scores
     */
    public function findBestMatches($promptText, $limit = null)
    {
        $limit = $limit ?? $this->maxResults;
        $promptText = mb_strtolower(trim($promptText));

        // Get all prompts with their primary answers
        $prompts = Prompt::with('primaryAnswer')->get();

        $matches = [];

        foreach ($prompts as $prompt) {
            $score = $this->combinedScore($promptText, $prompt->normalized_question);

            $matches[] = [
                'id' => $prompt->id,
                'question' => $prompt->question,
                'answer' => $prompt->primaryAnswer ? $prompt->primaryAnswer->answer : 'No answer available',
                'score' => $score,
                'is_favorite' => $prompt->is_favorite,
                'usage_count' => $prompt->usage_count,
            ];
        }

        // Sort by score descending
        usort($matches, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Get top matches
        $topMatches = array_slice($matches, 0, $limit);

        // Check if best match meets threshold
        $found = !empty($topMatches) && $topMatches[0]['score'] >= $this->threshold;

        return [
            'found' => $found,
            'threshold' => $this->threshold,
            'results' => $found ? $topMatches : [],
            'all_matches' => $matches, // For logging purposes
        ];
    }

    /**
     * Get current threshold
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * Set threshold
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;
        Setting::setValue('similarity_threshold', $threshold);
    }
}
