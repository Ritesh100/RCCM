<?php

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
Route::get('users/create', [AdminController::class, 'createUsers'])->name('admin.users.create')->middleware('auth');
Route::post('users/store', [AdminController::class, 'storeUsers'])->name('admin.users.store')->middleware('auth');
Route::get('users/edit/{id}', [AdminController::class, 'editUsers'])->name('admin.users.edit')->middleware('auth');
Route::put('users/update/{id}', [AdminController::class, 'updateUsers'])->name('admin.users.update')->middleware('auth');
Route::delete('users/delete/{id}', [AdminController::class, 'deleteUsers'])->name('admin.users.delete')->middleware('auth');

Route::get('/admin/document',[AdminController::class, 'showDocument'])->name('admin.document');
Route::delete('/document/{id}/delete', [AdminController::class, 'deleteDocument'])->name('document.delete');

Route::get('/admin/invoice',[AdminController::class, 'showInvoice'])->name('admin.invoice');
Route::get('/admin/create-invoice', [AdminController::class, 'createInvoice'])->name('admin.createInvoice');




//company
Route::get('/rcPartner/dashboard', function () {
    return view('company.dashboard');
})->name('company.dashboard');

Route::get('/rcPartner/profile/edit', [CompanyController::class, 'editProfile'])->name('company.profile.edit');
Route::get('/rcPartner/profile/users', [CompanyController::class, 'getUsers'])->name('company.profile.users');
Route::post('/rcPartner/profile/update', [CompanyController::class, 'updateProfile'])->name('company.profile.update');
Route::get('/rcPartner/payslips', [CompanyController::class, 'showPayslips'])->name('company.payslips');
Route::get('/rcPartner/payslipsPdf/{userId}/{weekRange}', [CompanyController::class, 'generatePayslip'])->name('company.generatepayslip');

