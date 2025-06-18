<?php

use App\Http\Controllers\Api\ContractorController;
use App\Http\Controllers\Api\MailsAdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MyRequestController;
use App\Http\Controllers\PurchasedDesignController;
use App\Http\Controllers\RequestContractorController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RequestDesignerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DesignerController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
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
Route::post('/contractors/{id}/portfolio', [ContractorController::class, 'updatePortfolio']);
Route::get('admin/designers', [DesignerController::class, 'showPreviewPortfolio']);
Route::post('/designers/{id}/portfolio', [DesignerController::class, 'updatePortfolio']);

Route::get('/customerservice', [MailsAdminController::class, 'userHistory']);
Route::get('/customerservice/{id}', [MailsAdminController::class, 'show']);
Route::post('/customerservice', [MailsAdminController::class, 'store'])->name('customerservice.store');

Route::get('/user/purchased-designs', [PurchasedDesignController::class, 'userPurchasedDesigns']);
Route::post('/contractors/{id}/request', [RequestContractorController::class, 'store']);
Route::post('/designers/{id}/request', [RequestDesignerController::class, 'store']);

Route::post('/chat/send', [ChatController::class, 'send']);
Route::get('/chat/{userId}', [ChatController::class, 'getChats']);
Route::get('/purchaseddesigns', [PurchasedDesignController::class, 'index']);

// routes for soft polling refresh every 2 seconds
Route::get('/request/{id}', [RequestController::class, 'showApi']);
Route::get('/myrequest/{id}', [MyRequestController::class, 'showApi']);
Route::get('/requests', [RequestController::class, 'indexApi']);
});