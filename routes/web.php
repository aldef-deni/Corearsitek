<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// ---------- Halaman depan (company profile) ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/portofolio', [PageController::class, 'portfolio'])->name('portfolio');
Route::get('/layanan', [PageController::class, 'services'])->name('services');
Route::get('/tentang', [PageController::class, 'about'])->name('about');

// ---------- Admin ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('contents', [ContentController::class, 'edit'])->name('contents.edit');
        Route::put('contents', [ContentController::class, 'update'])->name('contents.update');

        Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit']);
        Route::resource('features', FeatureController::class)->except(['show', 'create', 'edit']);
        Route::resource('galleries', GalleryController::class)->except(['show', 'create', 'edit']);
    });
});