<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Properties
    Route::get('/properties', [App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/{property}', [App\Http\Controllers\Admin\PropertyController::class, 'show'])->name('properties.show');
    Route::get('/properties/{property}/edit', [App\Http\Controllers\Admin\PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [App\Http\Controllers\Admin\PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [App\Http\Controllers\Admin\PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::post('/properties/{property}/publish', [App\Http\Controllers\Admin\PropertyController::class, 'publish'])->name('properties.publish');
    Route::post('/properties/{property}/unpublish', [App\Http\Controllers\Admin\PropertyController::class, 'unpublish'])->name('properties.unpublish');
    
    // Leads
    Route::get('/leads', [App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/{lead}', [App\Http\Controllers\Admin\LeadController::class, 'show'])->name('leads.show');
    Route::post('/leads/export', [App\Http\Controllers\Admin\LeadController::class, 'export'])->name('leads.export');
    
    // Scraper
    Route::get('/scraper', [App\Http\Controllers\Admin\ScraperController::class, 'index'])->name('scraper.index');
    Route::post('/scraper/run', [App\Http\Controllers\Admin\ScraperController::class, 'run'])->name('scraper.run');
    
    // Users
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});
