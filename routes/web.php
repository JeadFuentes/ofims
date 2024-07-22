<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/user',[UserController::class,'user'])->name('user.user');
Route::get('/devices',[UserController::class,'devices'])->name('user.devices');
Route::get('/alarm',[UserController::class,'alarm'])->name('user.alarm');
Route::get('/respond',[UserController::class,'respond'])->name('user.respond');