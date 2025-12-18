<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;

Route::get('/', [MedicineController::class,'index']);

Route::get('purchasehistory', [SaleController::class, "index"]);

Route::get('classes', [ClassController::class, 'index'])->name('classesview');

Route::get('students', [StudentController::class, 'index'])->name('studentsview');
