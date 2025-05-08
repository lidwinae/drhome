<?php

use App\Http\Controllers\Api\MailsAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\Api\DesignController;

// Route::middleware(['auth:sanctum'])->group(function () {
Route::get('designers', [DesignerController::class, 'index']);
Route::get('designers/{id}', [DesignerController::class, 'show']);

Route::prefix('designs')->group(function () {
    Route::get('/', [DesignController::class, 'index']);
    Route::get('/{id}', [DesignController::class, 'show']);
    Route::patch('/{id}', [DesignController::class, 'update']);
    Route::delete('/{id}', [DesignController::class, 'destroy']);
});

Route::get('admins', [MailsAdminController::class, 'index']);
Route::get('admins/{id}', [MailsAdminController::class, 'show']);
Route::patch('admins/{id}/reply', [MailsAdminController::class, 'update']);

Route::post('email/send', [MailsAdminController::class, 'send']);
Route::get('email/history', [MailsAdminController::class, 'riwayat']);
// });