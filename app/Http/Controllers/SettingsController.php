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

    /**
     * Clear all data
     */
    public function clearData(Request $request)
    {
        $type = $request->input('type', 'logs');

        switch ($type) {
            case 'logs':
                SimilarityLog::truncate();
                $message = 'Similarity logs cleared';
                break;
            case 'all':
                Prompt::truncate();
                SimilarityLog::truncate();
                $message = 'All data cleared';
                break;
            default:
                $message = 'Invalid type';
        }

        return back()->with('success', $message);
    }

    /**
     * Get database storage size (approximation)
     */
    private function getStorageSize()
    {
        $size = Prompt::count() * 500 + SimilarityLog::count() * 300; // Rough estimate in bytes

        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

}
