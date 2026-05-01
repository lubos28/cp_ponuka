<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ZakaznikController;
use App\Http\Controllers\PonukaController;
use Illuminate\Support\Facades\Route;

// public (bez loginu)
Route::get('/', function () {
    return redirect('/ponuka');
});

// login dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 všetko zamknuté za login
Route::middleware(['auth'])->group(function () {

    Route::get('/ponuka', [PonukaController::class, 'index']);
    
    Route::get('/produkty', [ProductController::class, 'index']);
    Route::post('/produkty/import', [ProductController::class, 'import']);
    Route::post('/produkty/reset', [ProductController::class, 'reset']);

    Route::get('/zakaznici', [ZakaznikController::class, 'index']);
    Route::post('/zakaznici', [ZakaznikController::class, 'store']);

    Route::get('/archiv', [PonukaController::class, 'archiv']);

    // profile (z breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// auth routes (login/register)
require __DIR__.'/auth.php';