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


}
