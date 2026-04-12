<?php

use Illuminate\Support\Facades\Route;

// Trang chủ dành cho Sinh viên
Route::get('/', function () {
    return view('user.index');
});

// Trang Dashboard dành cho Admin
Route::get('/admin', function () {
    return view('admin.dashboard');
});
