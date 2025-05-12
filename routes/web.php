<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

// user designer kontraktor
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('build', function () {
        return Inertia::render('Build');
    })->name('build');

    Route::get('design', function () {
        return Inertia::render('Design');
    })->name('design');

<<<<<<< HEAD
Route::get('designers/{id}', function ($id) {
    return Inertia::render('DesignerDetail', [
        'designerId' => $id
    ]);
})->name('designerdetail');

    Route::get('designers/{id}/request', function ($id) {
        return Inertia::render('RequestDesign', [
            'designerId' => $id
        ]);
    })->name('designer.request');

Route::get('api/designers', [DesignerController::class, 'index']);
Route::get('api/designers/{id}', [DesignerController::class, 'show']);

Route::get('api/designs', [DesignController::class, 'index']);
Route::get('api/designs/{id}', [DesignController::class, 'show']);
=======
    Route::get('request', function () {
        return Inertia::render('Request');
    })->name('request');
});
>>>>>>> 812b0d1a05007441f8f153255ad05f78d01401ed

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
