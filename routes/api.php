<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrowdfundingController;
use App\Http\Controllers\UserController;

// Authentication routes

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post(uri: '/login', action: [AuthController::class, 'login']);
    Route::post('/admin-login', [AuthController::class, 'adminLogin']);
    Route::post('/superadmin-login', [AuthController::class, 'superAdminLogin']);
});


// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/{id}', [UserController::class, 'updateProfile']);

    Route::get('/qr', [UserController::class, 'getQrText']);
    Route::get('/qr/image', [UserController::class, 'getQrImage']);


   
    // Crowdfunding routes
    Route::prefix(prefix: 'crowdfunding')->group(function () {

        Route::get('/campaigns', [CrowdfundingController::class, 'campaigns']);
        Route::get('/campaign/{id}', [CrowdfundingController::class, 'campaignDetail']);
        Route::post('/donate', [CrowdfundingController::class, 'donate']);
        Route::get('/donations', [CrowdfundingController::class, 'donationHistory']);
    });
   

     Route::prefix('admin')->middleware('admin')->group(function () {
        Route::post('/scan-qr', [AdminController::class, 'scanQr']);
    });
   
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/crowdfunding/webhook', [CrowdfundingController::class, 'handleWebhook']);