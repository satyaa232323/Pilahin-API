<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Authentication routes

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post(uri: '/login', action: [AuthController::class, 'login']);
    Route::post('/admin-login', [AuthController::class, 'adminLogin']);
    Route::post('/superadmin-login', [AuthController::class, 'superAdminLogin']);

});


// Protected routes
Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/{id}', [UserController::class, 'updateProfile']);


    Route::get('/qr', [UserController::class, 'getQrText']);
    Route::get('/qr/image', [UserController::class, 'getQrImage']);

    // Route::get('/waste/history', [UserController::class, 'wasteHistory']);
    // Route::get('/waste/detail/{id}', [UserController::class, 'wasteDetail']);

    // Route::get('/crowdfunding/campaigns', [UserController::class, 'campaigns']);
    // Route::get('/crowdfunding/campaign/{id}', [UserController::class, 'campaignDetail']);
    // Route::post('/crowdfunding/donate', [UserController::class, 'donate']);
    // Route::get('/crowdfunding/donations', [UserController::class, 'donationHistory']);

    // Route::get('/vouchers', [UserController::class, 'vouchers']);
    // Route::get('/voucher/{id}', [UserController::class, 'voucherDetail']);
    // Route::post('/voucher/redeem', [UserController::class, 'redeemVoucher']);
    // Route::get('/voucher/redeemed', [UserController::class, 'redeemedVouchers']);

    Route::post('/logout', [AuthController::class, 'logout']);
});