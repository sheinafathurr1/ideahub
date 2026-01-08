<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;

// --- 1. HALAMAN UTAMA ---
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard.index') : redirect()->route('login');
});

// --- 2. GUEST ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- 3. AUTH (USER) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Survey
    Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
    Route::post('/survey/draft', [SurveyController::class, 'saveDraft'])->name('survey.save_draft');
    Route::post('/survey/submit', [SurveyController::class, 'store'])->name('survey.store');
    Route::get('/survey/history/{id}', [SurveyController::class, 'show'])->name('survey.show');
});