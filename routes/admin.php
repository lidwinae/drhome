<?php

use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\MailsAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function () {
    // halaman utama admin menampilkan mails to admin (pekerjaan utama admin)
    Route::get('/', function () {
        return Inertia::render('admin/Admin');
    })->name('admin');

    // bagian reply mails oleh admin
    Route::patch('/mails/{mail}/reply', [MailsAdminController::class, 'update']);

    // halaman email (aplikasi eksternal dengan google mail)
    Route::get('/email', function () {
        return Inertia::render('admin/Mail');
    })->name('email');

    // halaman new untuk manajemen design
    Route::prefix('new')->group(function () {
        Route::get('/', function () {
            return Inertia::render('admin/New');
        })->name('new');

        Route::get('/design', function () {
            return Inertia::render('admin/NewDesign');
        })->name('newd');

        Route::post('/design', [DesignController::class, 'store'])->name('news');
    });

    Route::prefix('add')->group(function () {
        Route::get('/contractor', function () {
            return Inertia::render('admin/AddContractor');
        });
        Route::patch('/contractor', [UserController::class, 'update'])->name('upd');
    });

    Route::get('/ban', function () {
        return Inertia::render('admin/Ban');
    })->name('ban');
    
    Route::patch('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::patch('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

   
});

