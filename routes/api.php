<?php

use App\Http\Controllers\Api\AppearanceController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ConsentConfigController;
use App\Http\Controllers\Api\ConsentAnalyticsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ClientProfileController;
use App\Http\Controllers\Api\CompanyAndDomainController;

// Consent configuration route
Route::get('/consent/config/{settingsId}', [ConsentConfigController::class, 'getConfig']);

// Consent analytics route
Route::post('/consent/analytics', [ConsentAnalyticsController::class, 'recordAnalytics']);


// Consent configuration route for a specific user
Route::resource('users', App\Http\Controllers\API\UserController::class);
Route::resource('client-profiles', ClientProfileController::class);
Route::post('/company-domain', [CompanyAndDomainController::class, 'save']);
Route::post('/appearance', [AppearanceController::class, 'save']);


Route::post('/users/otp', [UserController::class, 'verifyOtp']);
Route::post('/users/password', [UserController::class, 'updatePassword']);
