<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', [UserController::class,'welcome']);

Route::get('/download', [FileController::class, 'export']);

Route::get('/export-users-pdf', [FileController::class, 'print_pdf']);

Route::get('/view-users-pdf', [FileController::class, 'view_pdf']);

Route::post('/upload-excel', [FileController::class, 'uploadExcel']);







