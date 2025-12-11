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


}
