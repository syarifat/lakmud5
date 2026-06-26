<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbsensiApiController;

Route::get('/materi', [AbsensiApiController::class, 'getMateri']);
Route::post('/absensi', [AbsensiApiController::class, 'recordAbsensi']);
