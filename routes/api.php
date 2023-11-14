<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::post('register', [AuthController::class, 'register'])->name('user.register');


Route::middleware('auth:api')->group(function () {
    Route::post('profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::post('total-info', [AuthController::class, 'getTotalInfo'])->name('user.total_info');
    Route::get('participant-list', [AuthController::class, 'getParticipant'])->name('user.participant');
    Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');
});
