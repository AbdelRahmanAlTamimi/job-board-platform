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
    Route::put('companies/{company}/restore', [CompanyController::class, 'restore'])
        ->withTrashed()
        ->name('companies.restore');

    // Job Application Resource Routes
    Route::resource('job-applications', JobApplicationController::class);
    Route::put('job-applications/{jobApplication}/restore', [JobApplicationController::class, 'restore'])
        ->withTrashed()
        ->name('job-applications.restore');
    // Job Category Resource Routes
    Route::resource('job-categories', JobCategoryController::class);
    Route::put('job-categories/{jobCategory}/restore', [JobCategoryController::class, 'restore'])
        ->withTrashed()
        ->name('job-categories.restore');

    // Job Vacancy Resource Routes
    Route::resource('job-vacancies', JobVacancyController::class);
    Route::put('job-vacancies/{jobVacancy}/restore', [JobVacancyController::class, 'restore'])
        ->withTrashed()
        ->name('job-vacancies.restore');

    // User Resource Routes
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
