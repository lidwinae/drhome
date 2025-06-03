<?php

use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\AvatarController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

// user designer kontraktor
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('build', function () {
        return Inertia::render('Build_group/Build');
    })->name('build');

    Route::get('design', function () {
        return Inertia::render('Design_group/Design');
    })->name('design');

    Route::get('design/{id}', function ($id) {
        return Inertia::render('Design_group/DesignDetail', [
            'designId' => $id
        ]);
    })->name('designdetail');

    Route::get('profile', function () {
        return Inertia::render('Profile');
    })->name('profile');

    Route::post('/profile', [AvatarController::class, 'updateAvatar']);
    Route::post('/profile/background', [AvatarController::class, 'updateBackground']);
    Route::post('/profile/update', [AvatarController::class, 'updateProfile']);
    Route::post('/profile/update-specialty', [AvatarController::class, 'updateSpecialty']);
    Route::post('/profile/update-about', [AvatarController::class, 'updateAbout']);

    Route::get('designers/{id}', function ($id) {
        return Inertia::render('Build_group/DesignerDetail', [
            'designerId' => $id
        ]);
    })->name('designerdetail');

    Route::get('designers/{id}/request', function ($id) {
        return Inertia::render('Build_group/RequestDesign', [
            'designerId' => $id
        ]);
    })->name('designer.request');

    Route::get('request', function () {
        return Inertia::render('Request');
    })->name('request');

    Route::get('/customerservice', function () {
        return Inertia::render('CustomerService');
    })->name('customerservice');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
