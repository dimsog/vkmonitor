<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\AccessTokenController;
use App\Http\Controllers\Api\GroupsController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/auth/redirect', [AuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.user');

Route::middleware('auth')->group(static function () {
    Route::post('/api/group/create', [GroupController::class, 'create']);
    Route::post('/api/group/delete', [GroupController::class, 'delete']);
    Route::get('/api/group/read/{vkGroupId}', [GroupController::class, 'read']);
    Route::post('/api/access-token/check', [AccessTokenController::class, 'check']);
    Route::get('/api/groups', [GroupsController::class, 'index']);
    Route::get('/api/users/{groupId}/{page?}', [UsersController::class, 'index']);

    // settings
    Route::get('/api/settings', [SettingsController::class, 'index']);
    Route::post('/api/settings/store', [SettingsController::class, 'store']);
    Route::get('/api/user/read', [UserController::class, 'read']);
    Route::post('/api/user/logout', [UserController::class, 'logout']);
});
