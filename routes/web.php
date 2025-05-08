<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\Api\DesignController;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

Route::get('build', function () {
    return Inertia::render('Build');
})->middleware(['auth', 'verified'])->name('build');

Route::get('design', function () {
    return Inertia::render('Design');
})->middleware(['auth', 'verified'])->name('design');

Route::get('designers/{id}', function ($id) {
    return Inertia::render('DesignerDetail', [
        'designerId' => $id
    ]);
})->name('designerdetail');

Route::get('api/designers', [DesignerController::class, 'index']);
Route::get('api/designers/{id}', [DesignerController::class, 'show']);

Route::get('api/designs', [DesignController::class, 'index']);
Route::get('api/designs/{id}', [DesignController::class, 'show']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
