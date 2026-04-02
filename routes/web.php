<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;

// --- 1. HALAMAN UTAMA ---
// Route::get('/', function () {
//     return Auth::check() ? redirect()->route('dashboard.index') : redirect()->route('login');
// });
    // ========== LANDING PAGE (PUBLIC) ==========
Route::get('/', [LandingController::class, 'index'])->name('landing.home');
Route::get('/universities', [LandingController::class, 'universities'])->name('landing.universities');
Route::get('/universities/{slug}', [LandingController::class, 'universityDetail'])->name('landing.university-detail');
Route::get('/news', [LandingController::class, 'news'])->name('landing.news');
Route::get('/news/{slug}', [LandingController::class, 'newsDetail'])->name('landing.news-detail');

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
    Route::post('/survey/save-draft', [SurveyController::class, 'saveDraft'])->name('survey.save_draft');
    Route::post('/survey/submit', [SurveyController::class, 'store'])->name('survey.store');
    Route::get('/survey/history/{id}', [SurveyController::class, 'show'])->name('survey.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/submission/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/submission/{id}/update', [AdminController::class, 'updateStatus'])->name('admin.update');
    
    // --- ROUTE BARU ---
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    // Route untuk download laporan
    Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('admin.reports.export');
    Route::get('/history', [AdminController::class, 'history'])->name('admin.history');

    // ========== NEWS ROUTES ==========
    Route::get('/news', [NewsController::class, 'index'])->name('admin.news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('admin.news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('admin.news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('admin.news.destroy');
});