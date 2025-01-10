<?php
use App\Exports\TimesheetExport;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;


Route::get('/', [LoginController::class, 'showUserLoginForm'])->name('userLogin.form');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route::get('/companyLogin', [LoginController::class, 'showCompanyLoginForm'])->name('companyLogin.form');
Route::post('/rcPartnerLogin', [LoginController::class, 'Companylogin'])->name('companyLogin');
Route::post('/rcPartnerLogout', [LoginController::class, 'companyLogout'])->name('companyLogout');
Route::get('/rcPartnerLogin', [LoginController::class, 'showCompanyLoginForm'])->name('companyLogin');
Route::get('/rcPartner/timeSheet',[CompanyController::class, 'showTimeSheet'])->name('company.timeSheet');
Route::put('/timesheet/{id}/update-status', [CompanyController::class, 'updateStatus'])->name('timesheet.updateStatus');
Route::put('/timesheet/update/{id}', [CompanyController::class, 'updateTimesheet'])->name('timesheet.updateTimeSheet');
Route::get('/rcPartner/document',[CompanyController::class, 'showDocument'])->name('company.document');
Route::get('/rcPartner/leave', [CompanyController::class, 'showLeave'])->name('company.leave');



//for User
Route::get('/rcLogin', [LoginController::class, 'showUserLoginForm'])->name('userLogin.form');
Route::post('/rcLogin', [LoginController::class, 'userLogin'])->name('userLogin');
Route::post('/rcLogout', [LoginController::class, 'userLogout'])->name('userLogout');
Route::get('/rc/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
Route::get('/rc/timeSheet',[UserController::class, 'showTimeSheet'])->name('user.timeSheet');
Route::post('/rc/timeSheet', [UserController::class, 'storeTimeSheet'])->name('timeSheet.store');
Route::get('/rc/document',[UserController::class, 'showDocument'])->name('user.document');
Route::post('/rc/documentPost',[UserController::class, 'storeDocument'])->name('user.storeDocument');
Route::get('/rc/leave', [UserController::class, 'updateLeave'])->name('user.leave');
Route::get('/rc/payslips', [UserController::class, 'showPayslips'])->name('user.payslips');
Route::get('/rc/payslipsPdf', [UserController::class, 'generatePayslipsPdf'])->name('user.payslipsPdf');
Route::get('/user/profile', [UserController::class, 'showProfile'])->name('user.profile');
Route::put('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
Route::get('/user/privacy', [UserController::class, 'privacy'])->name('user.privacy');


// Admin Dashboard
Route::get('/admin-dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard')->middleware('auth');
Route::get('/admin/company', function () {
    return view('admin.company');
})->name('admin.company')->middleware('auth');
Route::get('/admin/users', function () {
    return view('admin.users');
})->name('admin.users')->middleware('auth');

Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile')->middleware('auth');;
Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update')->middleware('auth');;
Route::get('admin/company', [AdminController::class, 'showCompany'])->name('admin.company')->middleware('auth');
Route::get('company/create', [AdminController::class, 'createCompany'])->name('admin.company.create')->middleware('auth');
Route::post('company/store', [AdminController::class, 'storeCompany'])->name('admin.company.store')->middleware('auth');
Route::get('company/edit/{id}', [AdminController::class, 'editCompany'])->name('admin.company.edit')->middleware('auth');
Route::put('company/update/{id}', [AdminController::class, 'updateCompany'])->name('admin.company.update')->middleware('auth');
Route::delete('company/delete/{id}', [AdminController::class, 'deleteCompany'])->name('admin.company.delete')->middleware('auth');

Route::get('admin/users', [AdminController::class, 'showUsers'])->name('admin.users')->middleware('auth');
Route::get('/admin/payslips', [AdminController::class, 'showPayslips'])->name('admin.payslips');
Route::get('/admin/edit-payslip/{userId}/{weekRange}', [AdminController::class, 'editPayslip'])->name('admin.editPayslip');
Route::put('/admin/update-payslip/{id}', [AdminController::class, 'updatePayslip'])
    ->name('admin.updatePayslip');
    Route::post('/admin/toggle-payslip-status', [AdminController::class, 'togglePayslipStatus'])
    ->name('admin.togglePayslipStatus');

Route::post('/admin/payslips/delete', [AdminController::class, 'deletePayslip'])->name('admin.deletePayslip');
Route::post('/admin/payslips/restore', [AdminController::class, 'restorePayslip'])->name('admin.restorePayslip');
   
Route::delete('/timesheet/{id}', [AdminController::class, 'deletePayslip'])->name('timesheet.delete');
Route::post('/admin/add-payslip', [AdminController::class, 'addPayslip'])->name('admin.addPayslip');

Route::get('/admin/payslipsPdf/{userId}/{weekRange}', [AdminController::class, 'generatePayslip'])->name('admin.generatepayslip');
Route::get('/admin/leave', [AdminController::class, 'showLeave'])->name('admin.leave');


Route::post('/admin/update-week-range', [AdminController::class, 'updateWeekRange'])
     ->name('admin.update.week-range');
Route::get('users/create', [AdminController::class, 'createUsers'])->name('admin.users.create')->middleware('auth');
Route::post('users/store', [AdminController::class, 'storeUsers'])->name('admin.users.store')->middleware('auth');
Route::get('users/edit/{id}', [AdminController::class, 'editUsers'])->name('admin.users.edit')->middleware('auth');
Route::put('users/update/{id}', [AdminController::class, 'updateUsers'])->name('admin.users.update')->middleware('auth');
Route::delete('users/delete/{id}', [AdminController::class, 'deleteUsers'])->name('admin.users.delete')->middleware('auth');

Route::get('/admin/document',[AdminController::class, 'showDocument'])->name('admin.document');
Route::delete('/document/{id}/delete', [AdminController::class, 'deleteDocument'])->name('document.delete');

Route::get('/admin/invoice',[AdminController::class, 'showInvoice'])->name('admin.invoice');
Route::get('/admin/create-invoice', [AdminController::class, 'createInvoice'])->name('admin.createInvoice');
Route::post('/admin/invoicePost/{rc_partner_id}', [AdminController::class, 'storeInvoice'])->name('admin.storeInvoice');
Route::get('/admin/generateInvoice/{id}', [AdminController::class, 'generateInvoicePdf'])->name('admin.invoicePdf');
Route::get('/admin/invoice/{id}', [AdminController::class, 'editInvoice'])->name('admin.editInvoice');
Route::put('/admin/invoice/{id}', [AdminController::class, 'updateInvoice'])->name('admin.invoice.update');
Route::get('/get-previous-credits/{invoice_for}', [AdminController::class, 'getPreviousCredits']);

Route::delete('/admin/invoice/{id}', [AdminController::class, 'destroyInvoice'])->name('admin.deleteInvoice');


Route::get('/admin/timesheets', [AdminController::class, 'showAllTimesheets'])
->name('admin.timesheets');
Route::put('admin/timesheet/bulk-update', [AdminController::class, 'bulkUpdate'])
    ->name('admin.timesheet.bulkUpdate');


// View timesheets for a specific company
Route::get('/admin/company/{companyId}/timesheets', [AdminController::class, 'showCompanyTimesheets'])
->name('admin.company.timesheets');

// Update timesheet status
Route::put('/admin/timesheet/{id}/update-status', [AdminController::class, 'updateStatus'])
->name('admin.timesheet.updateStatus');

// Update timesheet details
Route::put('/admin/timesheet/update/{id}', [AdminController::class, 'updateTimesheet'])
    ->name('timesheet.updateTimesheet');

//company
Route::get('/rcPartner/dashboard', function () {
    return view('company.dashboard');
})->name('company.dashboard');

Route::get('/rcPartner/profile/edit', [CompanyController::class, 'editProfile'])->name('company.profile.edit');
Route::get('/rcPartner/profile/users', [CompanyController::class, 'getUsers'])->name('company.profile.users');
Route::post('/rcPartner/profile/update', [CompanyController::class, 'updateProfile'])->name('company.profile.update');
Route::get('/rcPartner/payslips', [CompanyController::class, 'showPayslips'])->name('company.payslips');
Route::get('/rcPartner/privacy', [CompanyController::class, 'privacy'])->name('company.privacy');

Route::get('/rcPartner/payslipsPdf/{userId}/{weekRange}', [CompanyController::class, 'generatePayslip'])->name('company.generatepayslip');
Route::get('/company/invoice',[CompanyController::class, 'showInvoice'])->name('company.invoice');
Route::get('/company/generateInvoice/{id}', [CompanyController::class, 'generateInvoicePdf'])->name('company.invoicePdf');
Route::put('company/timesheet/bulk-update', [CompanyController::class, 'bulkUpdate'])
    ->name('company.timesheet.bulkUpdate');


//export timesheet

Route::get('export-timesheets/all', function () {
    return Excel::download(new TimesheetExport(), 'all_timesheets.xlsx');
})->name('export.timesheets.all');

Route::get('export-timesheets/approved', function () {
    return Excel::download(new TimesheetExport('approved'), 'approved_timesheets.xlsx');
})->name('export.timesheets.approved');

Route::get('export-timesheets/pending', function () {
    return Excel::download(new TimesheetExport('pending'), 'pending_timesheets.xlsx');
})->name('export.timesheets.pending');

Route::get('/company/timesheet/export', [CompanyController::class, 'exportTimesheets'])
    ->name('company.timesheet.export.all');
Route::get('/company/timesheet/export/approved', [CompanyController::class, 'exportTimesheets'])
    ->name('company.timesheet.export.approved')
    ->defaults('status', 'approved');
Route::get('/company/timesheet/export/pending', [CompanyController::class, 'exportTimesheets'])
    ->name('company.timesheet.export.pending')
    ->defaults('status', 'pending');

    //user
    Route::get('/timesheet/export/approved', [UserController::class, 'exportApproved'])->name('timesheet.export.approved');
Route::get('/timesheet/export/pending', [UserController::class, 'exportPending'])->name('timesheet.export.pending');
Route::get('/timesheet/export/all', [UserController::class, 'exportAll'])->name('timesheet.export.all');

Route::patch('/payslips/{id}/toggle-disable', [AdminController::class, 'toggleDisable'])->name('payslips.toggleDisable');


Route::get('/privacy', function () {
    return view('privacy');
});
