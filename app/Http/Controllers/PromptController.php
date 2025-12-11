<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\SimilarityLog;
use App\Services\SimilarityService;
use Illuminate\Http\Request;

class PromptController extends Controller
{
    protected $similarityService;

    public function __construct(SimilarityService $similarityService)
    {
        $this->similarityService = $similarityService;
    }

    /**
     * Show homepage
     */
    public function index()
    {
        $recentPrompts = Prompt::with('primaryAnswer')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $favoritePrompts = Prompt::with('primaryAnswer')
            ->where('is_favorite', true)
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact('recentPrompts', 'favoritePrompts'));
    }

    /**
     * Process prompt and find matches
     */
    public function search(Request $request)
    {
        $request->validate([
            'prompt_text' => 'required|string|min:3',
        ]);

        $promptText = $request->input('prompt_text');

        // Find best matches using similarity service
        $result = $this->similarityService->findBestMatches($promptText);

        // Log the search
        $log = SimilarityLog::create([
            'input_prompt' => $promptText,
            'matched_prompt_id' => $result['found'] ? $result['results'][0]['id'] : null,
            'similarity_score' => $result['found'] ? $result['results'][0]['score'] : null,
            'found_match' => $result['found'],
            'top_matches' => array_slice($result['all_matches'], 0, 5), // Store top 5 for analysis
        ]);

        // If match found, increment usage count
        if ($result['found']) {
            $matchedPrompt = Prompt::find($result['results'][0]['id']);
            if ($matchedPrompt) {
                $matchedPrompt->incrementUsage();
            }
        }

        // Return JSON for AJAX or view for regular requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'found' => $result['found'],
                'message' => $result['found'] ? 'Matches found' : 'NOT_FOUND',
                'results' => $result['results'],
                'threshold' => $result['threshold'],
            ]);
        }

        return view('results', [
            'found' => $result['found'],
            'results' => $result['results'],
            'inputPrompt' => $promptText,
            'threshold' => $result['threshold'],
            'logId' => $log->id,
        ]);
    }

    /**
     * Toggle favorite
     */
    public function toggleFavorite($id)
    {
        $prompt = Prompt::findOrFail($id);
        $prompt->is_favorite = !$prompt->is_favorite;
        $prompt->save();

        return back()->with('success', 'Favorite status updated');
    }

    /**
     * Delete prompt
     */
    public function destroy($id)
    {
        $prompt = Prompt::findOrFail($id);
        $prompt->delete();

        return back()->with('success', 'Prompt deleted successfully');
    }
}
