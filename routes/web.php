<?php

use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\MyRequestController;
use App\Http\Controllers\PurchasedDesignController;
use App\Http\Controllers\RequestController;
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

    Route::get('/build/contractor', function () {
        return Inertia::render('contractor/BuildContractor');
    })->name('buildcontractor');

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

    Route::get('contractors/{id}', function ($id) {
        return Inertia::render('contractor/ContractorDetail', [
            'designerId' => $id
        ]);
    })->name('contractordetail');

    Route::get('designers/{id}/request', function ($id) {
        return Inertia::render('Build_group/RequestDesigner', [
            'designerId' => $id
        ]);
    })->name('designer.request');

    Route::get('/contractors/{id}/request', function ($id) {
        return Inertia::render('contractor/RequestContractor', [
            'contractorId' => $id
        ]);
    });

    Route::get('design/{id}/purchase', function ($id) {
        return Inertia::render('PurchaseDesign', [
            'designId' => $id
        ]);
    })->name('purchase.design');

    Route::post('/purchase', [PurchasedDesignController::class, 'store'])
        ->name('purchase.post');
    Route::get('/design/{id}/is-purchased', [PurchasedDesignController::class, 'isPurchased'])
        ->name('design.isPurchased');

    Route::get('/customerservice', function () {
        return Inertia::render('CustomerService');
    })->name('customerservice');

    Route::get('/myrequest', [MyRequestController::class, 'index']);

    // designer contractor only
    Route::middleware('designer_contractor')->group(function () {
        Route::get('/request', [RequestController::class, 'index']);
        
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
