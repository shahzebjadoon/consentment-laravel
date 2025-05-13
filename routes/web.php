<?php

use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Frontend\NewLoginController;
use App\Http\Controllers\Frontend\NewRegisterController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PricePlanController;

// Home page redirect to new login
Route::get('/', function() {
    return redirect('/new/login');
});

// New login page
Route::get('/new/login', [NewLoginController::class, 'showLoginForm'])->name('frontend.new.login');

// New register page
Route::get('/new/register', [NewRegisterController::class, 'showRegistrationForm'])->name('frontend.new.register');

// Dashboard (protected by auth middleware)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('frontend.dashboard')
    ->middleware('auth');

// Authentication routes
Route::group(['namespace' => 'App\Http\Controllers\Frontend\Auth', 'as' => 'frontend.auth.'], function () {
    Route::post('login', 'LoginController@login')->name('login');
    Route::get('logout', 'LoginController@logout')->name('logout');
    
    // Registration routes
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');
});

// Language switcher
Route::get('lang/{lang}', [LocaleController::class, 'change'])->name('locale.change');

// Admin routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    includeRouteFiles(__DIR__.'/backend/');
});

// Company routes
Route::post('/companies', [App\Http\Controllers\Frontend\CompanyController::class, 'store'])
    ->name('frontend.companies.store')
    ->middleware('auth');
    
    
 // Company Dashboard routes
Route::group(['prefix' => 'companies/{id}', 'as' => 'frontend.companies.', 'middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Frontend\CompanyViewController::class, 'index'])->name('view');
    Route::get('/configurations', [App\Http\Controllers\Frontend\CompanyViewController::class, 'configurations'])->name('configurations');
    Route::get('/geolocation', [App\Http\Controllers\Frontend\CompanyViewController::class, 'geolocation'])->name('geolocation');
    Route::get('/users', [App\Http\Controllers\Frontend\CompanyViewController::class, 'users'])->name('users');
    Route::get('/details', [App\Http\Controllers\Frontend\CompanyViewController::class, 'details'])->name('details');
});


// Configuration routes

Route::post('/companies/{id}/configurations', [App\Http\Controllers\Frontend\ConfigurationController::class, 'store'])
    ->name('frontend.configurations.store')
    ->middleware('auth');
    
    
// Configuration edit route
Route::get('/companies/{company_id}/configurations/{config_id}/edit', 
    [App\Http\Controllers\Frontend\ConfigurationController::class, 'edit'])
    ->name('frontend.configurations.edit')
    ->middleware('auth');
    
    
Route::put('/companies/{company_id}/configurations/{config_id}', 
    [App\Http\Controllers\Frontend\ConfigurationController::class, 'update'])
    ->name('frontend.configurations.update')
    ->middleware('auth');
    
    
// Analytics routes
Route::group(['prefix' => 'companies/{company_id}/configurations/{config_id}/analytics', 'middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Frontend\AnalyticsController::class, 'index'])
        ->name('frontend.analytics.index');
    Route::get('/comparison', [App\Http\Controllers\Frontend\AnalyticsController::class, 'comparison'])
        ->name('frontend.analytics.comparison');
    Route::get('/granular', [App\Http\Controllers\Frontend\AnalyticsController::class, 'granular'])
        ->name('frontend.analytics.granular');
    Route::post('/filter', [App\Http\Controllers\Frontend\AnalyticsController::class, 'filter'])
        ->name('frontend.analytics.filter');
    Route::get('/download', [App\Http\Controllers\Frontend\AnalyticsController::class, 'downloadReport'])
        ->name('frontend.analytics.download');
});


// Service Settings routes
Route::group(['prefix' => 'companies/{company_id}/configurations/{config_id}/service-settings', 'middleware' => 'auth'], function () {
    Route::get('/scanner', [App\Http\Controllers\Frontend\ServiceSettingsController::class, 'scanner'])
        ->name('frontend.service-settings.scanner');
    Route::get('/services', [App\Http\Controllers\Frontend\ServiceSettingsController::class, 'services'])
        ->name('frontend.service-settings.services');
    Route::get('/categories', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'index'])
    ->name('frontend.service-settings.categories');
});


// Appearance routes
Route::group(['prefix' => 'companies/{company_id}/configurations/{config_id}/appearance', 'middleware' => 'auth'], function () {
    Route::get('/layout', [App\Http\Controllers\Frontend\AppearanceController::class, 'layout'])
        ->name('frontend.appearance.layout');
    Route::get('/styling', [App\Http\Controllers\Frontend\AppearanceController::class, 'styling'])
        ->name('frontend.appearance.styling');
});


// Content routes
Route::group(['prefix' => 'companies/{company_id}/configurations/{config_id}/content', 'middleware' => 'auth'], function () {
    Route::get('/first-layer', [App\Http\Controllers\Frontend\ContentController::class, 'firstLayer'])
        ->name('frontend.content.first-layer');
    Route::get('/second-layer', [App\Http\Controllers\Frontend\ContentController::class, 'secondLayer'])
        ->name('frontend.content.second-layer');
    Route::get('/labels', [App\Http\Controllers\Frontend\ContentController::class, 'labels'])
        ->name('frontend.content.labels');
});


// Implementation routes
Route::group(['prefix' => 'companies/{company_id}/configurations/{config_id}/implementation', 'middleware' => 'auth'], function () {
    Route::get('/script-tag', [App\Http\Controllers\Frontend\ImplementationController::class, 'scriptTag'])
        ->name('frontend.implementation.script-tag');
    Route::get('/embeddings', [App\Http\Controllers\Frontend\ImplementationController::class, 'embeddings'])
        ->name('frontend.implementation.embeddings');
    Route::get('/data-layer', [App\Http\Controllers\Frontend\ImplementationController::class, 'dataLayer'])
        ->name('frontend.implementation.data-layer');
    Route::get('/ab-testing', [App\Http\Controllers\Frontend\ImplementationController::class, 'abTesting'])
        ->name('frontend.implementation.ab-testing');
});



    
    
    // Preview route
Route::get('/companies/{company_id}/configurations/{config_id}/preview', [App\Http\Controllers\Frontend\PreviewController::class, 'preview'])
    ->name('frontend.preview.consent-preview')
    ->middleware('auth');

    Route::get('/preview-iframe/{config_id}',  [App\Http\Controllers\Frontend\PreviewController::class, 'iframePreview'])
    ->middleware('auth')
    ->name('frontend.preview.iframe-preview');
    
    
    
// geolocation route
Route::get('/companies/{company_id}/configurations/{config_id}/geolocation',
[App\Http\Controllers\Frontend\CompanyViewController::class, 'geolocation'])
    ->name('frontend.company.geolocation.index')
    ->middleware('auth');
    
    
    
    // Preview route
Route::get('/companies/{company_id}/configurations/{config_id}/preview', [App\Http\Controllers\Frontend\PreviewController::class, 'preview'])
    ->name('frontend.preview.consent-preview')
    ->middleware('auth');

    Route::get('/preview-iframe/{config_id}',  [App\Http\Controllers\Frontend\PreviewController::class, 'iframePreview'])
    ->middleware('auth')
    ->name('frontend.preview.iframe-preview');
    
    
// Appearance save route
Route::post('/companies/{company_id}/configurations/{config_id}/appearance/save', 
    [App\Http\Controllers\Frontend\AppearanceController::class, 'saveAppearance'])
    ->name('frontend.appearance.save')
    ->middleware('auth');
    
    
    
// Consent Management Platform routes
Route::get('/consent/config/{settingsId}', [App\Http\Controllers\ConfigController::class, 'getConfig']);
Route::post('/consent/analytics', [App\Http\Controllers\ConfigController::class, 'recordAnalytics']);
Route::get('/js/cmp/{script}', [App\Http\Controllers\ScriptController::class, 'serveScript']);



// Content save route
Route::post('/companies/{company_id}/configurations/{config_id}/content/save', 
    [App\Http\Controllers\Frontend\ContentController::class, 'saveContent'])
    ->name('frontend.content.save')
    ->middleware('auth');
    

// Scanner routes
Route::post('/companies/{company_id}/configurations/{config_id}/scanner/scan', 
    [App\Http\Controllers\Frontend\ServiceSettingsController::class, 'startScan'])
    ->name('frontend.service-settings.scan')
    ->middleware('auth');
    
    
Route::post('/companies/{company_id}/configurations/{config_id}/scanner/add-service/{scan_id}', 
    [App\Http\Controllers\Frontend\ServiceSettingsController::class, 'addService'])
    ->name('frontend.service-settings.add-service')
    ->middleware('auth');  
    
    
Route::post('/companies/{company_id}/configurations/{config_id}/scanner/add-service/{scan_id}', 
    [App\Http\Controllers\Frontend\ServiceSettingsController::class, 'addService'])
    ->name('frontend.service-settings.add-service')
    ->middleware('auth');
    
Route::post('/companies/{company_id}/configurations/{config_id}/categories/{category_id}/update',
    [App\Http\Controllers\Frontend\ServiceSettingsController::class, 'updateCategory'])
    ->name('frontend.service-settings.update-category')
    ->middleware('auth');



Route::post('/companies/{company_id}/configurations/{config_id}/add-domain', 
    [App\Http\Controllers\Frontend\ConfigurationController::class, 'addDomain'])
    ->name('frontend.configurations.add-domain')
    ->middleware('auth');
    

Route::delete('/companies/{company_id}/configurations/{config_id}/delete-domain', 
    [App\Http\Controllers\Frontend\ConfigurationController::class, 'deleteDomain'])
    ->name('frontend.configurations.delete-domain')
    ->middleware('auth');
    
    
Route::post('/companies/{company_id}/configurations/{config_id}/add-domain', 
    [App\Http\Controllers\Frontend\ConfigurationController::class, 'addDomain'])
    ->name('frontend.configurations.add-domain')
    ->middleware('auth');
    
// New Categories routes
Route::prefix('company/{company_id}/configuration/{config_id}/new-categories')->name('frontend.new-categories.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'store'])->name('store');
    Route::put('/reorder', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'reorder'])->name('reorder');
    Route::put('/{category_id}', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'update'])->name('update');
    Route::delete('/{category_id}', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'destroy'])->name('destroy');
    Route::put('/{category_id}/toggle-essential', [App\Http\Controllers\Frontend\NewCategoriesController::class, 'toggleEssential'])->name('toggle-essential');
});

Route::post('/consent/analytics', [App\Http\Controllers\ConsentDataController::class, 'recordAnalytics']);





Route::get('/companies/{company_id}/bill',  [BillingController::class, 'getbill'])
    ->middleware('auth')
    ->name('frontend.companies.billings');


Route::get('/price-plans', [PricePlanController::class, 'index'])->name('price.plans');



