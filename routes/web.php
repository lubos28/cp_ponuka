<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ZakaznikController;
use App\Http\Controllers\PonukaController;
use App\Http\Controllers\ProfileController;

// redirect na login / ponuka
Route::get('/', function () {
    return redirect('/ponuka');
});

// 🔐 všetko zamknuté
Route::middleware(['auth'])->group(function () {

    // --- PONUKA ---
    Route::get('/nova-ponuka', [PonukaController::class, 'index']);
    Route::get('/ponuka', [PonukaController::class, 'index']);

    Route::post('/save-ponuka', [PonukaController::class, 'store']);
    Route::get('/archiv', [PonukaController::class, 'archiv']);

    Route::delete('/delete-ponuka/{id}', [PonukaController::class, 'delete']);
    Route::post('/delete-multiple-ponuky', [PonukaController::class, 'deleteMultiple']);

    Route::get('/archiv/detail/{id}', [PonukaController::class, 'show']);
    Route::get('/ponuka/pdf/{id}', [PonukaController::class, 'generatePdf'])->name('ponuka.pdf');
    Route::get('/ponuka/excel/{id}', [PonukaController::class, 'exportExcel']);
    Route::post('/archiv/update-title', [PonukaController::class, 'updateTitle']);

    // --- PRODUKTY ---
    Route::get('/produkty', [ProductController::class, 'index']);
    Route::get('/search-products', [ProductController::class, 'search']); // 🔥 dôležité pre našepkávač
    Route::post('/produkty/import', [ProductController::class, 'import']);
    Route::post('/produkty/reset', [ProductController::class, 'reset']);

    // --- ZÁKAZNÍCI ---
    Route::get('/zakaznici', [ZakaznikController::class, 'index']);
    Route::post('/zakaznici/store', [ZakaznikController::class, 'store']);
    Route::get('/zakaznici/edit/{id}', [ZakaznikController::class, 'edit']);
    Route::post('/zakaznici/update/{id}', [ZakaznikController::class, 'update']);
    Route::delete('/zakaznici/delete/{id}', [ZakaznikController::class, 'destroy']);

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// login / register routes
require __DIR__.'/auth.php';