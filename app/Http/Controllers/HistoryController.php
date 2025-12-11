<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\SimilarityLog;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Show all Q&A history
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');

        $query = Prompt::with('primaryAnswer');

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                    ->orWhereHas('primaryAnswer', function($q2) use ($search) {
                        $q2->where('answer', 'like', '%' . $search . '%');
                    });
            });
        }

        // Apply filters
        switch ($filter) {
            case 'favorites':
                $query->where('is_favorite', true);
                break;
            case 'most_used':
                $query->orderBy('usage_count', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('updated_at', 'desc');
        }

        $prompts = $query->paginate(20);
//
//        // Get statistics
//        $stats = [
//            'total_prompts' => Prompt::count(),
//            'total_searches' => SimilarityLog::count(),
//            'success_rate' => SimilarityLog::count() > 0
//                ? round((SimilarityLog::where('found_match', true)->count() / SimilarityLog::count()) * 100, 1)
//                : 0,
//            'favorites' => Prompt::where('is_favorite', true)->count(),
//        ];

        return view('history.index', compact('prompts', 'search', 'filter'));
    }

}
