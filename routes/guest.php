<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['visitor'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/development', [HomeController::class, 'development'])->name('development');
    Route::get('/public-services', [HomeController::class, 'publicServices'])->name('public-services');

    Route::name('organization-profiles.')->group(function () {
        Route::get('/organization-structure', [HomeController::class, 'organizationStructure'])->name('organization-structure');
        Route::get('/vision-and-mission', [HomeController::class, 'visionMission'])->name('vision-and-mission');
        Route::get('/personnel-profiles', [HomeController::class, 'personnelProfile'])->name('personnel-profiles');
    });

    Route::name('other-informations.')->group(function () {
        Route::get('/faqs', [HomeController::class, 'faq'])->name('faqs');
        Route::prefix('download-center')->name('download-center.')->group(function () {
            Route::get('/', [HomeController::class, 'downloadCenterIndex'])->name('index');
            Route::get('/{slug}', [HomeController::class, 'downloadCenterShow'])->name('show');
        });
    });

    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [HomeController::class, 'blogIndex'])->name('index');
        Route::get('/category/{slug}', [HomeController::class, 'blogCategory'])->name('category');
        Route::get('/{slug}', [HomeController::class, 'blogShow'])->name('show');
    });

    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});
