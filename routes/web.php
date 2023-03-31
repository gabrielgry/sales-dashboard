<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProfileController;
use App\Models\Sale;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', ['sales' => Sale::all()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/sale', [SaleController::class, 'create'])->name('sale.create');
    Route::get('/sale/{id}', [SaleController::class, 'edit'])->name('sale.edit');
    Route::post('/sale', [SaleController::class, 'store'])->name('sale.store');
    Route::patch('/sale/{id}', [SaleController::class, 'update'])->name('sale.update');
    Route::delete('/sale/{ìd}', [SaleController::class, 'destroy'])->name('sale.destroy');
});

require __DIR__.'/auth.php';
