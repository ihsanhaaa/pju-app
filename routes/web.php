<?php

use App\Http\Controllers\KwhController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\PjuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function (){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // data user
    Route::resource('data-user', UserController::class);

    Route::resource('data-kecamatan', KecamatanController::class);

    Route::resource('/data-pju', PjuController::class);

    Route::resource('/data-kwh', KwhController::class);

    Route::get('/peta-pju', [KwhController::class, 'showMap'])->name('data-pju.peta');

    Route::post('/kwh/{id}/upload-fotos', [KwhController::class, 'uploadPhotoDetail'])->name('data-kwh.uploadFotos');
    Route::post('/pju/{id}/upload-fotos', [PjuController::class, 'uploadPhotoDetail'])->name('data-pju.uploadFotos');
    
    Route::get('/peta-lokasi', [KwhController::class, 'getLocations'])->name('getLocations');
    Route::post('/update-connection/', [KwhController::class, 'updateConnection'])->name('updateConnection');
});
