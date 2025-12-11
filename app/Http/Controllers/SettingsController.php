<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Prompt;
use App\Models\SimilarityLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        $stats = [
            'total_prompts' => Prompt::count(),
            'total_answers' => Prompt::has('primaryAnswer')->count(),
            'total_searches' => SimilarityLog::count(),
            'storage_used' => $this->getStorageSize(),
        ];

        return view('settings.index', compact('settings', 'stats'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'similarity_threshold' => 'required|numeric|min:0|max:1',
            'max_results' => 'required|integer|min:1|max:10',
            'algorithm_weight_levenshtein' => 'required|numeric|min:0|max:1',
            'algorithm_weight_similar_text' => 'required|numeric|min:0|max:1',
        ]);

        Setting::setValue('similarity_threshold', $request->input('similarity_threshold'));
        Setting::setValue('max_results', $request->input('max_results'));
        Setting::setValue('algorithm_weight_levenshtein', $request->input('algorithm_weight_levenshtein'));
        Setting::setValue('algorithm_weight_similar_text', $request->input('algorithm_weight_similar_text'));

        return back()->with('success', 'Settings updated successfully');
    }

}
