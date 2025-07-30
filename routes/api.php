<?php

declare(strict_types = 1);

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

// --
// Login Routes
Route::prefix('login')->group(function (): void {
    Route::post('/', [App\Http\Controllers\Api\LoginController::class, 'login'])->name('login');
})->middleware('guest');

 Route::post('/login/logout', [App\Http\Controllers\Api\LoginController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');
// --
// Transaction Routes
Route::prefix('transactions')->middleware('auth:sanctum')->group(function (): void {
    Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});
