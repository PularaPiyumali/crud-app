<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users', [UserController::class,'getUsers']);

Route::post('validusers', [UserController::class,'registeration']);

Route::post('users',[UserController::class,'register']);

Route::get('allusers',[UserController::class,'getAllUsers']);

