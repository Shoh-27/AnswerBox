<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [PromptController::class, 'index'])->name('home');

// Prompt search (AI Logic)
Route::post('/search', [PromptController::class, 'search'])->name('prompt.search');

// Prompt management
Route::post('/prompt/{id}/favorite', [PromptController::class, 'toggleFavorite'])->name('prompt.favorite');
Route::delete('/prompt/{id}', [PromptController::class, 'destroy'])->name('prompt.destroy');

// Answer management
Route::get('/answer/create', [AnswerController::class, 'create'])->name('answer.create');
Route::post('/answer', [AnswerController::class, 'store'])->name('answer.store');
Route::get('/answer/{id}/edit', [AnswerController::class, 'edit'])->name('answer.edit');
Route::put('/answer/{id}', [AnswerController::class, 'update'])->name('answer.update');
Route::post('/answer/{id}/helpful', [AnswerController::class, 'voteHelpful'])->name('answer.helpful');
Route::post('/answer/{id}/unhelpful', [AnswerController::class, 'voteUnhelpful'])->name('answer.unhelpful');

// History
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/history/logs', [HistoryController::class, 'logs'])->name('history.logs');

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::post('/settings/clear', [SettingsController::class, 'clearData'])->name('settings.clear');
