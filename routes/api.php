<?php

use App\Http\Controllers\SentencesController;
use Illuminate\Support\Facades\Route;

Route::post('meetings/{meeting}/sentences', [SentencesController::class, 'store'])
    ->name('meetings.sentences.store');
