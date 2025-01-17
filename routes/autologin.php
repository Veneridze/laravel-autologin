<?php

use Illuminate\Support\Facades\Route;
use Veneridze\Autologin\Controllers\AutologinController;
use Veneridze\Autologin\Facades\Autologin;

//Autologin::routes();
Route::get('/autologin/{token}', AutologinController::class)->name('autologin');