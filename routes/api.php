<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrowdfundingController;
use App\Http\Controllers\UserController;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/admin-login', [AuthController::class, 'adminLogin']);
    Route::post('/superadmin-login', [AuthController::class, 'superAdminLogin']);
});

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/{id}', [UserController::class, 'updateProfile']);

    Route::get('/qr', [UserController::class, 'getQrText']);
    Route::get('/qr/image', [UserController::class, 'getQrImage']);

     Route::get('/vouchers', [UserController::class, 'index']);
    Route::get('/vouchers/redeemed', [UserController::class, 'redeemVouchers']);
    Route::post('/vouchers/redeem', [UserController::class, 'redeem']);




    // Crowdfunding routes
    Route::prefix('crowdfunding')->group(function () {
        Route::get('/campaigns', [CrowdfundingController::class, 'campaigns']);
        Route::get('/campaign/{id}', [CrowdfundingController::class, 'campaignDetail']);
        Route::post('/campaign/{id}/donate', [CrowdfundingController::class, 'donate']);
        Route::get('/donations', [CrowdfundingController::class, 'donationHistory']);
        Route::get('/donation/{id}/status', [CrowdfundingController::class, 'checkDonationStatus']);

        // Manual update status (untuk testing/admin)
        Route::put('/donation/{id}/status', [CrowdfundingController::class, 'updateDonationStatus']);
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::post('/scan-qr', [AdminController::class, 'scanQr']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});

// Public routes untuk Midtrans callbacks
Route::prefix('crowdfunding')->group(function () {
    // WEBHOOK - Yang paling penting untuk update status otomatis
    Route::post('/webhook', [CrowdfundingController::class, 'handleWebhook']);

    // Redirect URLs - Untuk user experience
    Route::get('/payment/finish', [CrowdfundingController::class, 'paymentFinish']);
    Route::get('/payment/unfinish', [CrowdfundingController::class, 'paymentUnfinish']);
    Route::get('/payment/error', [CrowdfundingController::class, 'paymentError']);
});


Route::prefix('drop-points')->group(function () {
    Route::get('/', [UserController::class, 'index']); // Get all drop points
});

Route::prefix('waste-history')->group(function () {
    Route::get('/{user_id}', [UserController::class, 'getByUser']); // Get history by user
});



