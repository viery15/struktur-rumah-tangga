<?php

use App\Http\Controllers\RumahTanggaController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

//RUMAH TANGGA
Route::get('/', [RumahTanggaController::class, 'index'])->name('rumahTangga');
Route::get('/RumahTangga/create', [RumahTanggaController::class, 'create'])->name('rumahTangga');
Route::get('/RumahTangga/edit', [RumahTanggaController::class, 'edit'])->name('rumahTangga');
Route::post('/RumahTangga/store', [RumahTanggaController::class, 'store'])->name('rumahTangga');
Route::post('/RumahTangga/update', [RumahTanggaController::class, 'update'])->name('rumahTangga');

//PROVINSI
Route::get('/provinsi/getAll', [WilayahController::class, 'getAllProvinsi'])->name('wilayah');

//KOTA
Route::get('/kota/getByProvId', [WilayahController::class, 'getKotaByProvId'])->name('wilayah');

//KECAMATAN
Route::get('/kecamatan/getByKotaId', [WilayahController::class, 'getKcmByKotaId'])->name('wilayah');

//KELURAHAN
Route::get('/kelurahan/getByKcmId', [WilayahController::class, 'getKlhByKcmId'])->name('wilayah');
