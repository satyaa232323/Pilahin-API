<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('pages.home');
})->name('home');

Route::get(uri: '/input-user', action: function () {
    return view(view: 'pages.scanUser');
})->name('input');

Route::get('/user-data', function () {
    return view('pages.UserData');
})->name('user-data');

Route::get('/trash-data', function () {
    return view('pages.TrashData');
})->name('trash-data');
