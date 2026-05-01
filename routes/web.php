<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationSessionController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('games/', [GameController::class, 'index'])->name('games.index');
Route::get('games/{category}', [GameController::class, 'show'])->name('games.show');
Route::get('categories/', [CategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(ReservationController::class)->prefix('reservations')->group(function () {
        Route::get('/my', 'my')->name('reservations.my');
        Route::get('/create', 'create')->name('reservations.create');
        Route::post('/store', 'store')->name('reservations.store');
        Route::get('/{reservation}', 'show')->name('reservations.show');
        Route::get('/{reservation}/edit', 'edit')->name('reservations.edit');
        Route::put('/{reservation}/update', 'update')->name('reservations.update');
        Route::delete('/{reservation}/delete', 'delete')->name('reservations.delete');
    });

    Route::controller(ReservationSessionController::class)->prefix('my/sessions')->group(function () {
        Route::get('/', 'mySessions')->name('sessions.my');
    });

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/create', 'create')->name('categories.create');
        Route::post('/store', 'store')->name('categories.store');
        Route::get('/{category}/edit', 'edit')->name('categories.edit');
        Route::put('/{category}/update', 'update')->name('categories.update');
        Route::delete('/{category}/delete', 'delete')->name('categories.delete');
    });

    Route::controller(GameController::class)->prefix('games')->group(function () {
        Route::get('/create', 'create')->name('games.create');
        Route::post('/store', 'store')->name('games.store');
        Route::get('/{game}/status', 'status')->name('games.status');
        Route::get('/{game}/edit', 'edit')->name('games.edit');
        Route::put('/{game}/update', 'update')->name('games.update');
        Route::delete('/{game}/delete', 'delete')->name('games.delete');
    });

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations/{reservation}/status', [ReservationController::class, 'status'])->name('reservations.status');

    Route::controller(ReservationSessionController::class)->prefix('sessions')->group(function () {
        Route::get('/', 'index')->name('sessions.index');
        Route::post('/create-session', 'create')->name('sessions.create');
        Route::post('/{reservation}/start-session', 'start')->name('sessions.start');
        Route::post('/{reservation}/end-session', 'end')->name('sessions.end');
        Route::post('/{reservation}/update-session-game', 'updateGame')->name('sessions.updateGame');
    });

    Route::controller(TableController::class)->prefix('tables')->group(function () {
        Route::get('/', 'index')->name('tables.index');
        Route::get('/create', 'create')->name('tables.create');
        Route::post('/store', 'store')->name('tables.store');
        Route::get('/{table}/status', 'status')->name('tables.status');
        Route::get('/{table}/edit', 'edit')->name('tables.edit');
        Route::put('/{table}/update', 'update')->name('tables.update');
        Route::delete('/{table}/delete', 'delete')->name('tables.delete');
    });

});

require __DIR__.'/auth.php';
