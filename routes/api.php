<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\Api\DesignController;

Route::get('designers', [DesignerController::class, 'index']);
Route::get('designers/{id}', [DesignerController::class, 'show']);

Route::get('designs', [DesignController::class, 'index']);
Route::get('designs/{id}', [DesignController::class, 'show']);
