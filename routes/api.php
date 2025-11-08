<?php

use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\TimeEntryController;
use Illuminate\Support\Facades\Route;

// Debug route to test authentication
Route::middleware(['auth:sanctum'])->get('/test-auth', function () {
    return response()->json([
        'authenticated' => true,
        'user' => auth()->user()->only(['id', 'name', 'email']),
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Skills routes
    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/skills/user', [SkillController::class, 'userSkills']);
    Route::post('/skills/{skill}/start-tracking', [SkillController::class, 'startTracking']);

    // Time tracking routes
    Route::get('/time-entries', [TimeEntryController::class, 'index']);
    Route::get('/time-entries/active', [TimeEntryController::class, 'active']);
    Route::post('/time-entries/start', [TimeEntryController::class, 'start']);
    Route::post('/time-entries/stop', [TimeEntryController::class, 'stop']);
    Route::post('/time-entries/log-manual', [TimeEntryController::class, 'logManual']);

    // Stats routes
    Route::get('/skills/{skill}/stats', [TimeEntryController::class, 'skillStats']);
});
