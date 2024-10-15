<?php

use App\Http\Controllers\AudiencesController;
use App\Http\Controllers\MeetingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::resource('meetings', MeetingsController::class)
    ->only('store', 'show');
Route::get('meetings/{meeting}/join', [MeetingsController::class, 'join'])
    ->name('meetings.join');
Route::get('meetings/{meeting}/finish', [MeetingsController::class, 'finish'])
    ->name('meetings.finish');
Route::resource('meetings.audiences', AudiencesController::class)
    ->only('store', 'show');
Route::get('meetings/{meeting}/audiences/{audience}/summary', [AudiencesController::class, 'summary'])
    ->name('meetings.audiences.summary');
