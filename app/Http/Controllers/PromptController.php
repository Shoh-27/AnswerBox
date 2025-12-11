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
     * Delete prompt
     */
    public function destroy($id)
    {
        $prompt = Prompt::findOrFail($id);
        $prompt->delete();

        return back()->with('success', 'Prompt deleted successfully');
    }
}
