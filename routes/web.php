<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Models\Applicant;

Route::get('/', [HomeController::class, "index"])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post("/jobs", [JobController::class, "store"]);
    Route::get("/jobs/create", [JobController::class, "create"]);
    Route::get("/jobs/edit/{job}", [JobController::class, 'edit'])->name('jobs.edit');
    Route::put("/jobs/edit/{job}", [JobController::class, 'update'])->name('jobs.update');
    Route::delete("/jobs/delete/{job}", [JobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks');
    Route::post('/bookmarks/{job}', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{job}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    Route::post("/jobs/{job}/apply", [ApplicantController::class, 'store'])->name("applicants.store");
});

Route::get("/jobs", [JobController::class, "index"])->name('jobs.index');
Route::get("/jobs/{job}", [JobController::class, "show"])->name('jobs.show');
