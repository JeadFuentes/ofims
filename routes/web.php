<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::view('/', 'login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/logout',[UserController::class,'logout'])->name('user.logout');
Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/user',[UserController::class,'user'])->name('user.user');
    Route::get('/devices',[UserController::class,'devices'])->name('user.devices');
    Route::get('/alarm',[UserController::class,'alarm'])->name('user.alarm');
    Route::get('/respond',[UserController::class,'respond'])->name('user.respond');
    Route::get('/maps/{id}',[UserController::class,'maps'])->name('user.maps');
});