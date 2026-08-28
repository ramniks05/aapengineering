<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CityController as AdminCityController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\UpdateController as AdminUpdateController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/clients', [PageController::class, 'clients'])->name('clients');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/updates', [PageController::class, 'updates'])->name('updates.index');
Route::get('/updates/{slug}', [PageController::class, 'updateShow'])->name('updates.show');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');
Route::get('/enquiry', [EnquiryController::class, 'create'])->name('enquiry');
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');

Route::prefix('manage')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('projects', AdminProjectController::class)->except(['show']);
        Route::post('projects/{project}/media', [AdminProjectController::class, 'storeMedia'])->name('projects.media.store');
        Route::delete('projects/{project}/media/{medium}', [AdminProjectController::class, 'destroyMedia'])->name('projects.media.destroy');

        Route::get('cities', [AdminCityController::class, 'index'])->name('cities.index');
        Route::post('cities', [AdminCityController::class, 'store'])->name('cities.store');
        Route::put('cities/{city}', [AdminCityController::class, 'update'])->name('cities.update');
        Route::delete('cities/{city}', [AdminCityController::class, 'destroy'])->name('cities.destroy');

        Route::get('clients', [AdminClientController::class, 'index'])->name('clients.index');
        Route::post('clients', [AdminClientController::class, 'store'])->name('clients.store');
        Route::put('clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
        Route::delete('clients/{client}', [AdminClientController::class, 'destroy'])->name('clients.destroy');

        Route::get('gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery', [AdminGalleryController::class, 'store'])->name('gallery.store');
        Route::put('gallery/{gallery}', [AdminGalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{gallery}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::resource('updates', AdminUpdateController::class)->except(['show']);

        Route::get('enquiries', [AdminEnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/{enquiry}', [AdminEnquiryController::class, 'show'])->name('enquiries.show');
        Route::delete('enquiries/{enquiry}', [AdminEnquiryController::class, 'destroy'])->name('enquiries.destroy');
    });
});
