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

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
