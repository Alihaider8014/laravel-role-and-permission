<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/articles/add', [ArticleController::class, 'add'])->middleware('permission:add articles');
    Route::get('/articles/edit/{id}', [ArticleController::class, 'edit'])->middleware('permission:edit articles');
    Route::get('/articles/view', [ArticleController::class, 'view'])->middleware('permission:view articles');
    Route::get('/articles/delete/{id}', [ArticleController::class, 'delete'])->middleware('permission:delete articles');
    // Other routes...
});

//Create sanctum token
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
