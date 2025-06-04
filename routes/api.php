<?php

use App\Http\Controllers\Api\ContractorController;
use App\Http\Controllers\Api\MailsAdminController;
use App\Http\Controllers\PurchasedDesignController;
use App\Http\Controllers\RequestContractorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\UserController;

// Route::middleware(['auth:sanctum'])->group(function () {
Route::get('designers', [DesignerController::class, 'index']);
Route::get('designers/{id}', [DesignerController::class, 'show']);

Route::get('contractors', [ContractorController::class, 'index']);
Route::get('contractors/{id}', [ContractorController::class, 'show']);

Route::prefix('designs')->group(function () {
    Route::get('/', [DesignController::class, 'index']);
    Route::get('/{id}', [DesignController::class, 'show']);
    Route::post('/', [DesignController::class, 'store']);
    Route::patch('/{id}', [DesignController::class, 'update']);
    Route::delete('/{id}', [DesignController::class, 'destroy']);
});

Route::get('admins', [MailsAdminController::class, 'index']);
Route::get('admins/{id}', [MailsAdminController::class, 'show']);
Route::patch('admins/{id}/reply', [MailsAdminController::class, 'update']);

Route::post('email/send', [MailsAdminController::class, 'send']);
Route::get('email/history', [MailsAdminController::class, 'riwayat']);

Route::get('admin/users', [UserController::class, 'index']);
Route::get('admin/clients', [UserController::class, 'getClients']);
Route::get('admin/contractors', [ContractorController::class, 'showPreviewPortfolio']);

Route::get('/customerservice', [MailsAdminController::class, 'userHistory']);
Route::get('/customerservice/{id}', [MailsAdminController::class, 'show']);
Route::post('/customerservice', [MailsAdminController::class, 'store'])->name('customerservice.store');

Route::get('/user/purchased-designs', [PurchasedDesignController::class, 'userPurchasedDesigns']);
Route::post('/contractors/{id}/request', [RequestContractorController::class, 'store']);
// });