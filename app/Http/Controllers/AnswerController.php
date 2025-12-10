<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Show form to create new Q&A pair
     */
    public function create(Request $request)
    {
        $inputPrompt = $request->input('prompt', '');

        return view('answers.create', compact('inputPrompt'));
    }

    /**
     * Store new Q&A pair
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|min:3',
            'answer' => 'required|string|min:3',
        ]);

        // Create prompt
        $prompt = Prompt::create([
            'question' => $request->input('question'),
        ]);

        // Create answer
        Answer::create([
            'prompt_id' => $prompt->id,
            'answer' => $request->input('answer'),
            'is_primary' => true,
        ]);

        return redirect()->route('home')
            ->with('success', 'New Q&A pair added successfully! The AI has learned.');
    }


}
