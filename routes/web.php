<?php

use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminRoleController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\forum\CategoryController;
use App\Http\Controllers\forum\IndexController;
use App\Http\Controllers\forum\TopicController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('home');

Route::get('/categories/{category}/{slug?}', [CategoryController::class, 'show'])->name('category.show');

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login');
    });
Route::get('/register', [RegisterController::class, "index"])->name('register');
Route::get('/register', [RegisterController::class, "index"])->name('register');
Route::get('/profile/{value}', [ProfileController::class, "index"])->name('profile');
Route::post('/register', [RegisterController::class, "store"])->name('register');

Route::get('/dashboard', [AdminController::class, "index"])->name('dashboard');
Route::resource('/roles', AdminRoleController::class)->except(['create', 'show']);

Route::get('/logout', [LogoutController::class, "logout"])->name('logout');

Route::resource('topics', TopicController::class)->except('show');

Route::get('/topics/{topic}/{slug?}', [TopicController::class, "show"])->name('topic.show');

Route::get('/messages', [MessageController::class, "index"])->name('messages');

Route::get('/users',[UserController::class, "index"])->name('users.index');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard',[AdminController::class, "index"])->name('index');
    Route::resource('/categories', AdminCategoryController::class);
    Route::resource('/roles', AdminRoleController::class);

});






