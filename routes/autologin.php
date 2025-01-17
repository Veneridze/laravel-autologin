<?php

use Illuminate\Support\Facades\Route;
use Veneridze\Autologin\Controllers\AutologinController;
use Veneridze\Autologin\Facades\Autologin;

Route::middleware('web')->group(function () {
    Route::get('/autologin/{token}', AutologinController::class)->name('autologin');
});