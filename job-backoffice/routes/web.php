<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\UserController;


Route::middleware('auth')->group(function () {
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard');
    
    // Company Resource Routes
    Route::resource('companies', CompanyController::class);

    // Job Application Resource Routes
    Route::resource('job-applications', JobApplicationController::class);

    // Job Category Resource Routes
    Route::resource('job-categories', JobCategoryController::class);

    // Job Vacancy Resource Routes
    Route::resource('job-vacancies', JobVacancyController::class);

    // User Resource Routes
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
