<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('home');
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login');
    });
Route::get('/register', [RegisterController::class, "index"])->name('register');
Route::get('/register', [RegisterController::class, "index"])->name('register');
Route::get('/profile/{value}', [ProfileController::class, "index"])->name('profile');
Route::post('/register', [RegisterController::class, "store"])->name('register');

Route::get('/dashboard', [AdminController::class, "index"])->name('dashboard');
Route::resource('/roles', RoleController::class)->except(['create', 'show']);

Route::get('/logout', [LogoutController::class, "logout"])->name('logout');

Route::resource('topics', TopicController::class)->except('show');

Route::get('/topics/{topic}/{slug}', [TopicController::class, "show"])->name('topic.show');



