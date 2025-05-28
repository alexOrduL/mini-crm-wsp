<?php

use App\Http\Controllers\Api\V1\CompanyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\DealController;

Route::prefix('v1')->group(function () {
    Route::get('companies', [CompanyController::class, 'index']);
    Route::post('companies', [CompanyController::class, 'store']);
    Route::put('companies/{company}', [CompanyController::class, 'store']);
    Route::delete('companies/{company}', [CompanyController::class, 'destroy']);
    
    Route::get('contacts', [ContactController::class, 'index']);
    Route::post('companies/{companyId}/contacts', [CompanyController::class, 'attachContact']);
    Route::put('contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy']);

    
    Route::get('/deals', [DealController::class, 'index']);
    Route::post('/deals', [DealController::class, 'store']);
    Route::put('/deals/{deal}', [DealController::class, 'update']);
    Route::delete('/deals/{deal}', [DealController::class, 'destroy']);

});