<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\PenjualanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('levels', [LevelController::class, 'index']);
Route::post('levels', [LevelController::class, 'store']);
Route::get('levels/{level}', [LevelController::class, 'show']);
Route::put('levels/{level}', [LevelController::class, 'update']);
Route::delete('levels/{level}', [LevelController::class, 'destroy']);

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{user}', [UserController::class, 'show']);
Route::put('users/{user}', [UserController::class, 'update']);
Route::delete('users/{user}', [UserController::class, 'destroy']);

Route::get('category', [KategoriController::class, 'index']);
Route::post('category', [KategoriController::class, 'store']);
Route::get('category/{kategori}', [KategoriController::class, 'show']);
Route::put('category/{kategori}', [KategoriController::class, 'update']);
Route::delete('category/{kategori}', [KategoriController::class, 'destroy']);

Route::get('items', [BarangController::class, 'index']);
Route::post('items', [BarangController::class, 'store']);
Route::get('items/{barang}', [BarangController::class, 'show']);
Route::put('items/{barang}', [BarangController::class, 'update']);
Route::delete('items/{barang}', [BarangController::class, 'destroy']);

Route::get('sales', [PenjualanController::class, 'index']);
Route::post('sales', [PenjualanController::class, 'store']);
Route::get('sales/{penjualan}', [PenjualanController::class, 'show']);
Route::put('sales/{penjualan}', [PenjualanController::class, 'update']);
Route::delete('sales/{penjualan}', [PenjualanController::class, 'destroy']);

Route::post('/register1', App\Http\Controllers\Api\RegisterController::class)->name('register1');
