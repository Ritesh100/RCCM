<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;

// Authentication Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/companyLogin', [LoginController::class, 'showCompanyLoginForm'])->name('companyLogin.form');
Route::post('/companyLogin', [LoginController::class, 'Companylogin'])->name('companyLogin');
Route::post('/companyLogout', [LoginController::class, 'companyLogout'])->name('companyLogout');

// User Routes
Route::get('/userLogin', [LoginController::class, 'showUserLoginForm'])->name('userLogin.form');
Route::post('/userLogin', [LoginController::class, 'userLogin'])->name('userLogin');
Route::post('/userLogout', [LoginController::class, 'userLogout'])->name('userLogout');

// User Dashboard
Route::get('/user/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
Route::get('/user/timeSheet', [UserController::class, 'showTimeSheet'])->name('user.timeSheet');
Route::post('/user/timeSheet', [UserController::class, 'storeTimeSheet'])->name('timeSheet.store');

// Company Routes
Route::get('/company/timeSheet', [CompanyController::class, 'showTimeSheet'])->name('company.timeSheet');
Route::put('/timesheet/{id}/update-status', [CompanyController::class, 'updateStatus'])->name('timesheet.updateStatus');
Route::put('/timesheet/update/{id}', [CompanyController::class, 'updateTimesheet'])->name('timesheet.updateTimeSheet');

// Admin Routes with Middleware
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/admin/company', fn() => view('admin.company'))->name('admin.company');
    Route::get('/admin/users', fn() => view('admin.users'))->name('admin.users');

    Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
    Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Company Management
    Route::resource('company', AdminController::class)->names([
        'index' => 'admin.company.index',
        'create' => 'admin.company.create',
        'store' => 'admin.company.store',
        'edit' => 'admin.company.edit',
        'update' => 'admin.company.update',
        'destroy' => 'admin.company.delete',
    ]);

    // User Management
    Route::resource('users', AdminController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.delete',
    ]);
});

// Company Dashboard
Route::get('/company/dashboard', fn() => view('company.dashboard'))->name('company.dashboard');

// Company Profile Management
Route::get('/company/profile/edit', [CompanyController::class, 'editProfile'])->name('company.profile.edit');
Route::get('/company/profile/users', [CompanyController::class, 'getUsers'])->name('company.profile.users');
Route::post('/company/profile/update', [CompanyController::class, 'updateProfile'])->name('company.profile.update');
