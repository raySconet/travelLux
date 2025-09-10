<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourtCasesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/calendar', function () {
    return view('calendar');
})->middleware(['auth', 'verified'])->name('calendar');

Route::get('/category', function () {
    return view('category');
})->middleware(['auth', 'verified'])->name('category');

Route::get('/caseInfo', function(){
    return view('caseInfo');
})->middleware(['auth','verified'])->name('caseInfo');

Route::get('/getCategories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

Route::middleware('auth')->group(function () {
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::post('/cases/store', [CourtCasesController::class, 'store'])->name('cases.store');
});

require __DIR__.'/auth.php';
