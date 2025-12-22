<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\InsuranceController;
<<<<<<< HEAD
use App\Http\Controllers\SystemUsersController;


=======
use App\Http\Controllers\productConfigurationController;
>>>>>>> 338c7c7b1731e47b86d2a393167ca4c39dab31f9

Route::get('/', function () {
    return view('auth/login');
});


Route::get('/dashboard', function () {
    return redirect('/profile');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/users/store', [UserController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('store.user');

Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::post('/users/{user}/update', [UserController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('users.update');

Route::get('/permissions', [UserController::class, 'userPermissions'])
    ->middleware(['auth', 'verified', RoleMiddleware::class . ':super_admin'])
    ->name('permissions');

Route::post('/permissions/update',[UserController::class, 'updatePermissions'])
    ->middleware(['auth', 'verified'])
    ->name('permissions.update');

Route::get('/getUsersAndAssignedUsers', [UserController::class, 'getUsersAndAssignedUsers'])
    ->middleware(['auth', 'verified', RoleMiddleware::class . ':super_admin'])
    ->name('getUsersAndAssignedUsers');

Route::post('/assignViewAccess', [UserController::class, 'assignViewAccess'])
    ->middleware(['auth', 'verified', RoleMiddleware::class . ':super_admin'])
    ->name('assignViewAccess');

Route::get('/users/permissions/partial', [UserController::class, 'partial'])->name('permissions.partial');
Route::put('/users/{user}', [UserController::class, 'delete'])->name('user.delete');

Route::get('/getUsers', [UserController::class, 'index']);


Route::middleware('auth')->group(function () {

    Route::get('/insurance', [InsuranceController::class, 'index'])->name('insurance.index');
    Route::post('/insurance', [InsuranceController::class, 'store'])->name('insurance.store');
    Route::put('/insurance/{id}', [InsuranceController::class, 'update'])->name('insurance.update');
    Route::delete('/insurance/{id}', [InsuranceController::class, 'destroy'])->name('insurance.destroy');
    Route::get('/insurance/{id}/fetch', [InsuranceController::class, 'fetch'])->name('insurance.fetch');


});

Route::get('/user/can-create-case', [CourtCasesController::class, 'canCreateCase']);
Route::post('/update-event-user', [EventController::class, 'updateEventUser']);
Route::post('/update-case-user', [CourtCasesController::class, 'updateCaseUser']);


<<<<<<< HEAD
Route::get('/system-users', [SystemUsersController::class, 'index'])
    ->name('system-users.index');

Route::get('/system-users/{user}', [SystemUsersController::class, 'edit'])
    ->name('system-users.edit');
=======
Route::middleware('auth')->group(function () {
    Route::get('/productConfiguration', [ProductConfigurationController::class, 'index']);
    // Route::post('/insurance', [InsuranceController::class, 'store'])->name('insurance.store');
    // Route::put('/insurance/{id}', [InsuranceController::class, 'update'])->name('insurance.update');
    // Route::delete('/insurance/{id}', [InsuranceController::class, 'destroy'])->name('insurance.destroy');
    // Route::get('/insurance/{id}/fetch', [InsuranceController::class, 'fetch'])->name('insurance.fetch');
});
>>>>>>> 338c7c7b1731e47b86d2a393167ca4c39dab31f9

require __DIR__.'/auth.php';
