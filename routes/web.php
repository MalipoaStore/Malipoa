<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;

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
    return view('frontend.index');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashboard');
    Route::post('/user/update/profile', [UserController::class, 'UserUpdateProfile'])->name('user.update.profile');
    Route::get('/user/logout', [UserController::class, 'destroy'])->name('user.logout');
    Route::post('/change/password', [UserController::class, 'ChangePassword'])->name('user.change.password');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile/logout', [AdminController::class, 'Destroy'])->name('admin.profile.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'AdminProfileUpdate'])->name('admin.profile.update');
    Route::get('/admin/change/password', [AdminController::class, 'ChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'UpdatePassword'])->name('admin.update.password');
});

Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor.profile.logout', [VendorController::class, 'Destroy'])->name('vendor.profile.logout');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/update', [VendorController::class, 'VendorProfileUpdate'])->name('vendor.profile.update');
    Route::get('/vendor/change/password', [VendorController::class, 'ChangePassword'])->name('vendor.change.password');
    Route::post('/vendor/update/password', [VendorController::class, 'UpdatePassword'])->name('vendor.update.password');
});

Route::get('/admin/login', [AdminController::class, 'AdminLogin']);
Route::get('/vendor/login', [VendorController::class, 'VendorLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
