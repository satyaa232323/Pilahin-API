<?php

use App\Http\Controllers\Web\WebAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', action: [WebAdminController::class, 'login'])->name('login.post');
Route::post('/logout', [WebAdminController::class, 'logout'])->name('logout');

// Protected admin routes
Route::middleware(['web.auth'])->group(function () {
    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');

    Route::get('/input-user', function () {
        return view('pages.scanUser');
    })->name('input');

    Route::get('/user-data', function () {
        return view('pages.UserData');
    })->name('user-data');

    Route::get('/trash-data', function () {
        return view('pages.TrashData');
    })->name('trash-data');

    // API endpoints for web admin
    Route::post('/api/scan-qr', [WebAdminController::class, 'scanQr'])->name('scan-qr');
    Route::post('/api/submit-waste', [WebAdminController::class, 'submitWaste'])->name('submit-waste');
});