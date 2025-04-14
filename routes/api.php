<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ConsentConfigController;
use App\Http\Controllers\Api\ConsentAnalyticsController; 

// Consent configuration route
Route::get('/consent/config/{settingsId}', [ConsentConfigController::class, 'getConfig']);

// Consent analytics route
Route::post('/consent/analytics', [ConsentAnalyticsController::class, 'recordAnalytics']);