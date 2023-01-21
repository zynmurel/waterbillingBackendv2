<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangayPuroksController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConsumersController;
use App\Http\Controllers\ReadingsController;
use App\Http\Controllers\ServicePeriodController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',[AuthController::class,'register']);

//private routes
//Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::resource('/brgyprk', BarangayPuroksController::class);

    Route::resource('/consumer', ConsumersController::class);
    Route::get('/consumer/{consumer}', [ConsumersController::class, 'show']);

    Route::resource('/reading', ReadingsController::class);
    Route::get('/readingsBillingsPayments/{reading}', [ReadingsController::class, 'readingBillingsPayments']);
    Route::get('/inquire/{reading}', [ReadingsController::class, 'inquire']);
    Route::get('/meterReadings/{reading}', [ReadingsController::class, 'meterReadings']);
    Route::get('/showByServicePeriod/{month}/{year}', [ReadingsController::class, 'showByServicePeriod']);
    Route::get('/showReadBillPayConsumer/{id}/', [BillingController::class, 'showReadBillPayConsumer']);
    Route::get('/collectionReports/{year}/{month}', [ReadingsController::class, 'collectionReports']);
    Route::get('/consumerReport', [ReadingsController::class, 'consumerReport']);

    Route::resource('/settings', SettingsController::class);

    Route::resource('/user', UserController::class);
    Route::put('/userPassword/{id}', [UserController::class, 'updatePassword']);

    Route::resource('/billing', BillingController::class);
    
    Route::resource('/serviceperiod', ServicePeriodController::class); 
    Route::post('addmaui',[ConsumersController::class, 'add']);
//});

