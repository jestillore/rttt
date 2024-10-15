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
Route::resource('meetings.audiences', AudiencesController::class)
    ->only('store', 'show');
