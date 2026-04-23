<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\SystemUsersController;
use App\Http\Controllers\TimelineTasksController;
use App\Http\Controllers\AgencyProfileController;
use App\Http\Controllers\CustomersFormController;
use App\Http\Controllers\AutomatedEmailsController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\CommissionsRemittancesController;
use App\Http\Controllers\CheckWriterController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\productConfigurationController;
use App\Http\Controllers\OverallTaskDashboardController;
use App\Http\Controllers\MyOverallTaskDashboardController;
use App\Http\Controllers\OwnersDashboardController;
use App\Http\Controllers\CheckingInThisWeekController;
use App\Http\Controllers\CommissionClaimReportController;
use App\Http\Controllers\AgentsReportController;
use App\Http\Controllers\Report1099Controller;
use App\Http\Controllers\VendorReportController;
use App\Http\Controllers\CheckHistoryReportController;
use App\Http\Controllers\CurrentChecksReportController;
use App\Http\Controllers\ReservationsNotPaidByALTReportController;
use App\Http\Controllers\ReservationsPaidByALTReportController;
use App\Http\Controllers\UnknownReservationsReportController;
use App\Http\Controllers\BookedTripsByStateReportController;
use App\Http\Controllers\ReservationsChangesReportController;
use App\Http\Controllers\ReservationsByAgentReportController;
use App\Http\Controllers\ProductSalesByAgentReportController;
use App\Http\Controllers\AgentSalesByProductReportController;
use App\Http\Controllers\TotalSalesReportController;
use App\Http\Controllers\AgentExpensesReportController;
use App\Http\Controllers\AliasTotalSalesReportController;
use App\Http\Controllers\AliasTotalGrossCommissionReportController;
use App\Http\Controllers\AllReservationsByDateReportController;
use App\Http\Controllers\AllTripsByTravelDateReportController;
use App\Http\Controllers\Top15ExpensiveTripsReportController;
use App\Http\Controllers\CruisesReportController;
use App\Http\Controllers\ProductSalesWithDepositByAgentReportController;
use App\Http\Controllers\FinalAgencyCommissionReportController;
use App\Http\Controllers\BookedReservationsPerMonthReportController;
use App\Http\Controllers\ResleadsReportController;
use App\Http\Controllers\TotalCommissionReportController;
use App\Http\Controllers\HotelOnlyReportController;
use App\Http\Controllers\ClassicVacationsReportController;
use App\Http\Controllers\PleasantEvokeReportController;
use App\Http\Controllers\AppleVacationsReportController;
use App\Http\Controllers\TravelImpressionsReportController;
use App\Http\Controllers\VacationExpressReportController;
use App\Http\Controllers\ExpediaReportController;
use App\Http\Controllers\RebookingRateReportController;
use App\Http\Controllers\SalesReportController;

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

Route::middleware('auth')->group(function () {
    Route::get('/system-users', [SystemUsersController::class, 'index'])->name('system-users');
    Route::get('/system-users/create', [SystemUsersController::class, 'create'])->name('system-users.create');
    Route::get('/system-users/{user}', [SystemUsersController::class, 'edit'])->name('system-users.edit');

    Route::post('/user', [SystemUsersController::class, 'store'])->name('system-users.store');
    Route::put('/user/{user}', [SystemUsersController::class, 'update'])->name('system-users.update');
    Route::delete('/user/{user}', [SystemUsersController::class, 'destroy'])->name('system-users.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/productConfiguration', [ProductConfigurationController::class, 'index']);
});
Route::middleware('auth')->group(function () {
    Route::get('/timelinetasks', [TimelineTasksController::class, 'index'])->name('timelinetasks');
    Route::get('/timelinetasks/create', [TimelineTasksController::class,'create'])->name('timeline-tasks.create');
    Route::get('/timelinetasks/{timelineTask}', [TimelineTasksController::class, 'edit'])->name('timeline-tasks.edit');

    Route::post('/timelineTask', [TimelineTasksController::class, 'store'])->name('timelineTask.store');
    Route::put('/timelineTask/{timelineTask}', [TimelineTasksController::class, 'update'])->name('timelineTask.update');
    Route::delete('/timelineTask/{timelineTask}', [TimelineTasksController::class, 'destroy'])->name('timelineTask.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/agencyProfile', [AgencyProfileController::class, 'index'])->name('agencyProfile');
    Route::put('/agencyProfile', [AgencyProfileController::class, 'update'])->name('agencyProfile.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/customersForms', [CustomersFormController::class, 'index'])->name('customersForms');
    Route::get('/customersForm/create', [CustomersFormController::class,'create'])->name('customersForm.create');
    Route::get('/customers-form/{customerForm}', [CustomersFormController::class, 'edit'])->name('customersForm.edit');

    Route::post('/customersForm', [CustomersFormController::class, 'store'])->name('customersForm.store');
    Route::put('/customersForm/{customerForm}', [CustomersFormController::class,'update'])->name('customersForm.update');
    Route::delete('/customersForm/{customerForm}', [CustomersFormController::class,'destroy'])->name('customersForm.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/automatedEmails', [AutomatedEmailsController::class, 'index'])->name('automatedEmails');
    Route::get('/automatedEmails/create', [AutomatedEmailsController::class,'create'])->name('automatedEmails.create');
    Route::get('/automatedEmails/{automatedEmail}', [AutomatedEmailsController::class, 'edit'])->name('automatedEmails.edit');

    Route::post('/automatedEmail', [AutomatedEmailsController::class,'store'])->name('automatedEmails.store');
    Route::put('/automatedEmail/{automatedEmail}', [AutomatedEmailsController::class,'update'])->name('automatedEmails.update');
    Route::delete('/automatedEmail/{automatedEmail}', [AutomatedEmailsController::class,'destroy'])->name('automatedEmails.destroy');
    Route::patch('/automatedEmails/{automatedEmail}/toggle', [AutomatedEmailsController::class, 'toggle'])->name('automatedEmails.toggle');
});
Route::middleware('auth')->group(function () {
    Route::get('/itinerary', [ItineraryController::class, 'index']);
    Route::get('/itinerary/{itinerary}', [ItineraryController::class, 'edit'])->name('itinerary.edit');
});
Route::middleware('auth')->group(function(){
    Route::get('/customer-list', [CustomerController::class, 'index'])->name('customers.customerList');
    Route::get('/customer/create', [CustomerController::class,'create'])->name('customers.create');
    Route::get('/customer-list/{customer}',[CustomerController::class, 'edit'])->name('customers.customerDetails');

    Route::post('/customer', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customer/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/inviteNewCustomer', [CustomerController::class, 'inviteNewCustomer'])->name('customers.inviteNewCustomer');

    Route::post('/customer/{customer}/members', [CustomerController::class, 'storeFamilyMember'])->name('customers.familyMembers.store');
    Route::put('/family-members/{familyMember}', [CustomerController::class, 'updateFamilyMember'])->name('familyMembers.update');
    Route::delete('/members/{member}', [CustomerController::class, 'deleteFamilyMember'])->name('familyMembers.delete');
});
Route::middleware('auth')->group(function(){
    Route::get('/reservation-list', [ReservationController::class, 'index'])->name('reservations.reservationList');
    Route::get('/reservation-list/create', [ReservationController::class,'create'])->name('reservations.create');
    Route::get('/reservation-list/{reservation}',[ReservationController::class, 'edit'])->name('reservations.reservationDetails');

    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservations.store');
    Route::put('/reservation/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservation/{reservation}', [ReservationController::class ,'destroy'])->name('reservations.destroy');
    Route::delete('/reservations/bulk-delete', [ReservationController::class, 'bulkDelete'])->name('reservations.bulkDelete');

    Route::post('/reservation/{reservation}/tasks', [ReservationController::class, 'storeTask'])->name('reservations.tasks.store');
    Route::post('/tasks/{task}/toggle-complete', [ReservationController::class, 'toggleCompleteTask'])->name('tasks.toggleComplete');
    Route::put('/task/{task}', [ReservationController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{task}', [ReservationController::class, 'deleteTask'])->name('tasks.delete');
    
    Route::post('/reservation/{reservation}/payments', [ReservationController::class, 'storePayment'])->name('reservations.payments.store');
    Route::put('/payment/{payment}', [ReservationController::class, 'updatePayment'])->name('reservations.payments.update');
    Route::delete('/payment/{payment}', [ReservationController::class, 'deletePayment'])->name('reservations.payments.delete');

    Route::post('/traveler/{traveler}/toggle-include', [ReservationController::class, 'toggleIncludeTraveler'])->name('travelers.toggleInclude');

    Route::post('/reservation/{reservation}/diningNotes', [ReservationController::class, 'storeDiningNote'])->name('reservations.diningNotes.store');
    Route::post('/diningNotes/{diningNote}/toggle-cancel', [ReservationController::class, 'toggleCancelDiningNote'])->name('diningNotes.toggleCancel');
    Route::put('/diningNote/{diningNote}', [ReservationController::class, 'updateDiningNote'])->name('reservations.diningNotes.update');
    Route::delete('/diningNotes/{diningNote}', [ReservationController::class,'deleteDiningNote'])->name('diningNotes.delete');

    Route::post('/reservation/{reservation}/gifts', [ReservationController::class, 'storeGift'])->name('reservations.gifts.store');
    Route::put('/gift/{gift}', [ReservationController::class, 'updateGift'])->name('reservations.gifts.update');
    Route::delete('/gifts/{gift}', [ReservationController::class, 'deleteGift'])->name('gifts.delete');

    Route::post('/reservation/{reservation}/phoneNotes', [ReservationController::class, 'storePhoneNote'])->name('reservations.phoneNotes.store');
    Route::post('/phoneNotes/{phoneNote}/toggle-cancel', [ReservationController::class, 'toggleCancelPhoneNote'])->name('phoneNotes.toggleCancel');
    Route::put('/phoneNote/{phoneNote}', [ReservationController::class, 'updatePhoneNote'])->name('reservations.phoneNotes.update');
    Route::delete('/phoneNotes/{phoneNote}', [ReservationController::class, 'deletePhoneNote'])->name('phoneNotes.delete');

    Route::post('/reservation/{reservation}/commissionFees', [ReservationController::class, 'storeCommissionFee'])->name('reservations.commissionFees.store');
    Route::put('/commissionFee/{commissionFee}', [ReservationController::class, 'updateCommissionFee'])->name('reservations.commissionFees.update');
    Route::delete('/commissionFees/{commissionFee}', [ReservationController::class, 'deleteCommissionFee'])->name('commissionFees.delete');

    Route::get('/customers/{id}/active-reservations', [ReservationController::class, 'getActiveReservations']);
});
Route::middleware('auth')->group(function(){
    Route::get('/vendor-list', [VendorsController::class,'index'])->name('vendors.vendorList');
    Route::put('/vendor-list/{product}', [VendorsController::class,'update'])->name('vendors.update');
});
Route::middleware('auth')->group(function(){
    Route::get('/commissionRemittances', [CommissionsRemittancesController::class, 'index'])->name('commissions.commissionsRemittances');
    Route::get('/checkWriter', [CheckWriterController::class, 'index'])->name('commissions.checkWriter');
});
Route::middleware('auth')->group(function(){
    Route::get('/agentDashboard',[AgentDashboardController::class,'index'])->name('dashboards.agentDashboard');
    Route::get('/overallTaskDashboard', [OverallTaskDashboardController::class,'index'])->name('dashboards.overallTaskDashboard');
    Route::get('/myOverallTaskDashboard', [MyOverallTaskDashboardController::class,'index'])->name('dashboards.myOverallTaskDashboard');
    Route::get('/ownersDashboard', [OwnersDashboardController::class,'index'])->name('dashboards.ownersDashboard');
    Route::get('/checkingInThisWeek', [CheckingInThisWeekController::class,'index'])->name('dashboards.checkingInThisWeek');
});
Route::middleware('auth')->group(function(){
    Route::get('/vendorReport', [VendorReportController::class, 'index'])->name('reports.vendorReport');
    Route::get('/1099Report', [Report1099Controller::class,'index'])->name('reports.1099Report');
    Route::get('/checkHistoryReport', [CheckHistoryReportController::class,'index'])->name('reports.checkHistory');
    Route::get('/currentChecksReport', [CurrentChecksReportController::class,'index'])->name('reports.currentChecks');
    Route::get('/commissionClaimReport',[CommissionClaimReportController::class,'index'])->name('reports.commissionClaimReport');
    Route::get('/reservationsNotPaidByALTReport', [ReservationsNotPaidByALTReportController::class,'index'])->name('reports.reservationsNotPaidByALTReport');
    Route::get('/reservationsPaidByALTReport', [ReservationsPaidByALTReportController::class,'index'])->name('reports.reservationsPaidByALTReport');
    Route::get('/unknownReservationsReport',[UnknownReservationsReportController::class,'index'])->name('reports.unknownReservationsReport');
    Route::get('/bookedTripsByStateReport', [BookedTripsByStateReportController::class,'index'])->name('reports.bookedTripsByStateReport');
    Route::get('/reservationsChangesReport', [ReservationsChangesReportController::class,'index'])->name('reports.reservationsChangesReport');
    Route::get('/reservationsByAgentReport', [ReservationsByAgentReportController::class,'index'])->name('reports.reservationsByAgentReport');
    Route::get('/productSalesByAgentReport', [ProductSalesByAgentReportController::class,'index'])->name('reports.productSalesByAgentReport');
    Route::get('/agentSalesByProductReport', [AgentSalesByProductReportController::class,'index'])->name('reports.agentSalesByProductReport');
    Route::get('/totalSalesReport', [TotalSalesReportController::class,'index'])->name('reports.totalSalesReport');
    Route::get('/agentExpensesReport', [AgentExpensesReportController::class,'index'])->name('reports.agentExpensesReport');
    Route::get('/aliasTotalSalesReport', [AliasTotalSalesReportController::class,'index'])->name('reports.aliasTotalSalesReport');
    Route::get('/aliasTotalGrossCommission', [AliasTotalGrossCommissionReportController::class,'index'])->name('reports.aliasTotalGrossCommission');
    Route::get('/allReservationsByDateReport', [AllReservationsByDateReportController::class,'index'])->name('reports.allReservationsByDateReport');
    Route::get('/allTripsByTravelDateReport', [AllTripsByTravelDateReportController::class,'index'])->name('reports.allTripsByTravelDateReport');
    Route::get('/top15ExpensiveTripsReport', [Top15ExpensiveTripsReportController::class,'index'])->name('reports.top15ExpensiveTripsReport');
    Route::get('/cruisesReport', [CruisesReportController::class,'index'])->name('reports.cruisesReport');
    Route::get('/productSalesWithDepositByAgentReport', [ProductSalesWithDepositByAgentReportController::class,'index'])->name('reports.productSalesWithDepositByAgentReport');
    Route::get('/finalAgencyCommissionReport', [FinalAgencyCommissionReportController::class,'index'])->name('reports.finalAgencyCommissionReport');
    Route::get('/agentsReport', [AgentsReportController::class,'index'])->name('reports.agentsReport');
    Route::get('/bookedReservationsPerMonthReport', [BookedReservationsPerMonthReportController::class,'index'])->name('reports.bookedReservationsPerMonthReport');
    Route::get('/reservationsLeadReport', [ResleadsReportController::class,'index'])->name('reports.resleadsReport');
    Route::get('/totalCommissionReport', [TotalCommissionReportController::class,'index'])->name('reports.totalCommissionReport');
    Route::get('/hotelOnlyReport', [HotelOnlyReportController::class,'index'])->name('reports.hotelOnlyReport');
    Route::get('/classicVacationsReport', [ClassicVacationsReportController::class,'index'])->name('reports.classicVacationsReport');
    Route::get('/pleasantEvokeReport', [PleasantEvokeReportController::class,'index'])->name('reports.pleasantEvokeReport');
    Route::get('/appleVacationsReport', [AppleVacationsReportController::class,'index'])->name('reports.appleVacationsReport');
    Route::get('/travelImpressionsReport', [TravelImpressionsReportController::class,'index'])->name('reports.travelImpressionsReport');
    Route::get('/vacationExpressReport', [VacationExpressReportController::class,'index'])->name('reports.vacationExpressReport');
    Route::get('/expediaReport', [ExpediaReportController::class,'index'])->name('reports.expediaReport');
    Route::get('/rebookingRateReport', [RebookingRateReportController::class,'index'])->name('reports.rebookingReportRateReport');
    Route::get('/salesReport', [SalesReportController::class,'index'])->name('reports.salesReport');
});
require __DIR__.'/auth.php';
