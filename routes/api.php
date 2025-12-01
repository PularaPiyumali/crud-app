<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('users', [UserController::class,'getAllUsers']);

Route::post('validusers', [UserController::class,'register']);

Route::post('users',[UserController::class,'create']);

Route::get('allusers',[UserController::class,'findUsers']);

Route::put('users/{id}',[UserController::class,'update']);

Route::delete('users/{id}',[UserController::class,'delete']);

Route::get('/index', [UserController::class, 'index']);

Route::get('/categories', [UserController::class, 'showCategories']);

Route::post('/upload-file', [FileController::class, 'upload']);