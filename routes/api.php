<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\DatabasePerusahaanController;


Route::get('/artikel', [ArtikelController::class, 'index']);
Route::get('/artikel/{slug}', [ArtikelController::class, 'show']);
Route::get('/layanan', [LayananController::class, 'index']);
Route::get('/layanan/{slug}', [LayananController::class, 'show']);
Route::apiResource('database-perusahaan', DatabasePerusahaanController::class);
