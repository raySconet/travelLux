<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourtCasesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCourtCaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserCategoryController;
use App\Http\Controllers\UserController;
use App\Models\CourtCase;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\InsuranceCategoryController;


Route::get('/', function () {
    return view('auth/login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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
Route::post('/categoryUpdate/{categorie}', [CategoryController::class, 'update'])->name('categorie.update');
Route::post('/category/delete', [CategoryController::class, 'delete'])->name('category.delete');

Route::get('/getUsers', [UserController::class, 'index']);

Route::get('/getUsersCategories', [UserCategoryController::class, 'index']);

Route::post('/getEvents', [EventController::class, 'index']);
Route::put('/eventUpdate/{event}', [EventController::class, 'update'])->name('event.update');
Route::put('/eventDelete/{event}', [EventController::class, 'delete'])->name('event.delete');

Route::post('/getCases', [CourtCasesController::class, 'index']);
Route::put('/caseUpdate/{case}', [CourtCasesController::class, 'update'])->name('case.update');
Route::put('/caseDelete/{case}', [CourtCasesController::class, 'delete'])->name('case.delete');

Route::post('/getEventsNCases', [EventCourtCaseController::class, 'index']);
Route::get('/getEventsCases', [CategoryController::class, 'getEventsAndCases']);

Route::middleware('auth')->group(function () {
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::post('/cases/store', [CourtCasesController::class, 'store'])->name('cases.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/sections/store', [SectionController::class, 'store'])->name('sections.store');
    Route::get('/cases/{caseId}/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('sections/show/{id}', [SectionController::class, 'show']);
    Route::post('sections/update/{id}', [SectionController::class, 'update'])->name('sections.update');
    Route::post('/sections/soft-delete/{id}', [SectionController::class, 'softDelete']);

    Route::post('/todos/store', [TodoController::class, 'store'])->name('todos.store');
    Route::post('/todos/toggle-complete', [TodoController::class, 'toggleComplete'])->name('todos.toggleComplete');
    Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::post('/todos/update-status/{id}', [TodoController::class, 'updateStatus']);
    Route::get('/todos/{id}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/{id}', [TodoController::class, 'update'])->name('todos.update');

    Route::post('/cases/update-case-info/{id}', [CourtCasesController::class, 'updateCaseInfo'])->name('cases.updateCaseInfo');
    Route::get('/cases/{caseId}/main-info', [CourtCasesController::class, 'mainInfo'])->name('cases.mainInfo');
    Route::post('/save-textarea', [CourtCasesController::class, 'saveTextarea']);


    Route::get('/insurance', [InsuranceController::class, 'index'])->name('insurance.index');
    Route::post('/insurance', [InsuranceController::class, 'store'])->name('insurance.store');
    Route::put('/insurance/{id}', [InsuranceController::class, 'update'])->name('insurance.update');
    Route::delete('/insurance/{id}', [InsuranceController::class, 'destroy'])->name('insurance.destroy');
    Route::get('/insurance/{id}/fetch', [InsuranceController::class, 'fetch'])->name('insurance.fetch');

    Route::get('/getCategoriesInsurance', [InsuranceCategoryController::class, 'index']);

});

Route::get('/user/can-create-case', [CourtCasesController::class, 'canCreateCase']);
Route::post('/update-event-user', [EventController::class, 'updateEventUser']);
Route::post('/update-case-user', [CourtCasesController::class, 'updateCaseUser']);

require __DIR__.'/auth.php';
