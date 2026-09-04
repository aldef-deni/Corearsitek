<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdvantageController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

// ---------- Halaman depan (company profile) ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/portofolio', [PageController::class, 'portfolio'])->name('portfolio');
Route::get('/portofolio/{portfolio:slug}', [PageController::class, 'portfolioDetail'])->name('portfolio.show');
Route::get('/galeri', [PageController::class, 'gallery'])->name('gallery');
Route::get('/layanan', [PageController::class, 'services'])->name('services');
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');

// Pengajuan pembuatan desain dari halaman Kontak. Dibatasi agar satu pengirim
// tidak bisa membanjiri kotak masuk.
Route::post('/kontak', [SubmissionController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('contact.store');

// ---------- Admin ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profil administrator
        Route::get('profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profil', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profil/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.destroy');
        Route::put('profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Satu halaman untuk seluruh isi "Tentang CoreArsitek", termasuk logo klien.
        Route::get('tentang', [AboutController::class, 'edit'])->name('about.edit');
        Route::put('tentang', [AboutController::class, 'update'])->name('about.update');
        Route::delete('tentang/foto', [AboutController::class, 'removeProfileImage'])->name('about.image.destroy');

        Route::get('contents', [ContentController::class, 'edit'])->name('contents.edit');
        Route::put('contents', [ContentController::class, 'update'])->name('contents.update');

        Route::resource('banners', BannerController::class)->except(['show', 'create', 'edit']);
        Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit']);
        Route::resource('features', FeatureController::class)->except(['show', 'create', 'edit']);
        Route::resource('galleries', GalleryController::class)->except(['show', 'create', 'edit']);
        Route::resource('portfolios', PortfolioController::class)->except(['show']);
        Route::delete('portfolios/{portfolio}/images/{image}', [PortfolioController::class, 'destroyImage'])
            ->name('portfolios.images.destroy');
        Route::resource('advantages', AdvantageController::class)->except(['show', 'create', 'edit']);
        Route::post('clients/{client}/duplikat', [ClientController::class, 'duplicate'])->name('clients.duplicate');
        Route::resource('clients', ClientController::class)->only(['store', 'update', 'destroy']);
        // Pengajuan calon klien: hanya dibaca dan ditindaklanjuti, tidak dibuat manual.
        Route::get('pengajuan', [AdminSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('pengajuan/{submission}', [AdminSubmissionController::class, 'show'])->name('submissions.show');
        Route::put('pengajuan/{submission}', [AdminSubmissionController::class, 'update'])->name('submissions.update');
        Route::post('pengajuan/{submission}/dibaca', [AdminSubmissionController::class, 'toggleRead'])
            ->name('submissions.read');
        Route::delete('pengajuan/{submission}', [AdminSubmissionController::class, 'destroy'])
            ->name('submissions.destroy');

        Route::resource('testimonials', TestimonialController::class)->except(['show', 'create', 'edit']);
        Route::resource('process-steps', ProcessStepController::class)
            ->parameters(['process-steps' => 'processStep'])
            ->except(['show', 'create', 'edit']);
    });
});
