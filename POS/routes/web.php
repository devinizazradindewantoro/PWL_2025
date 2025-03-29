<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;

//Home
Route::get('/', [HomeController::class, 'index']);

//Product dengan Prefix
Route::prefix('Products')->group(function () {
    Route::get('/food-beverages', [ProductController::class, 'foodBeverages'])->name('category.food-beverage');
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth'])->name('category.beauty-health');
    Route::get('/home-care', [ProductController::class, 'homeCare'])->name('category.home-care');
    Route::get('/baby-kid', [ProductController::class, 'babyKid'])->name('category.baby-kid');
});

//User dengan parameter
Route::get('/user/{id}/name/{name}', [UserController::class, 'showProfile'])->name('user.profile');

//Sales
Route::get('/sales', [SalesController::class, 'index'])->name('sales');

Route::get('/', [WelcomeController::class, 'index']);

Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::group(['prefix' => 'user'], function () {
    Route::middleware(['authorize:ADM,MNG,STF,CUS'])->group(function () {
        Route::get('/', [UserController::class, 'index']);                             // Menampilkan halaman awal level user
        Route::post('/list', [UserController::class, 'list']);                         // menampilkan level user dalam bentuk json untuk datatables
        Route::get('/create', [UserController::class, 'create']);                      // menampilkan halaman form tambah level user
        Route::post('/', [UserController::class, 'store']);                            // menyimpan level user baru   
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);            // menampilkan halaman form tambah user Ajax     
        Route::post('/ajax', [UserController::class, 'store_ajax']);                   // menyimpan data user baru Ajax
        Route::get('/{id}', [UserController::class, 'show']);                          // Menampilkan detail level user
        Route::get('/{id}/edit', [UserController::class, 'edit']);                     // menampilkan halaman form edit level user
        Route::put('/{id}', [UserController::class, 'update']);                        // menyimpan perubahan level user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);           // Menampilkan halaman form edit user Ajax            
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);       // Menyimpan perubahan data user Ajax      
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);      // Untuk tampilkan form confirm delete user Ajax   
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);    // Untuk hapus data user Ajax
        Route::delete('/{id}', [UserController::class, 'destroy']);                    // menghapus level user
    });
});

Route::group(['prefix' => 'product'], function () {
    Route::middleware(['authorize:MNG,ADM'])->group(function () {
        Route::get('/', [ProductController::class, 'index']);                             // Menampilkan halaman awal level user
        Route::post('/list', [ProductController::class, 'list']);                         // menampilkan level user dalam bentuk json untuk datatables
        Route::get('/create', [ProductController::class, 'create']);                      // menampilkan halaman form tambah level user
        Route::post('/', [ProductController::class, 'store']);                            // menyimpan level user baru   
        Route::get('/create_ajax', [ProductController::class, 'create_ajax']);            // menampilkan halaman form tambah user Ajax     
        Route::post('/ajax', [ProductController::class, 'store_ajax']);                   // menyimpan data user baru Ajax
        Route::get('/{id}', [ProductController::class, 'show']);                          // Menampilkan detail level user
        Route::get('/{id}/edit', [ProductController::class, 'edit']);                     // menampilkan halaman form edit level user
        Route::put('/{id}', [ProductController::class, 'update']);                        // menyimpan perubahan level user
        Route::get('/{id}/edit_ajax', [ProductController::class, 'edit_ajax']);           // Menampilkan halaman form edit user Ajax            
        Route::put('/{id}/update_ajax', [ProductController::class, 'update_ajax']);       // Menyimpan perubahan data user Ajax      
        Route::get('/{id}/delete_ajax', [ProductController::class, 'confirm_ajax']);      // Untuk tampilkan form confirm delete user Ajax   
        Route::delete('/{id}/delete_ajax', [ProductController::class, 'delete_ajax']);    // Untuk hapus data user Ajax
        Route::delete('/{id}', [ProductController::class, 'destroy']);                    // menghapus level user
    });
});

Route::group(['prefix' => 'sales'], function () {
    Route::middleware(['authorize:STF'])->group(function () {
        Route::get('/', [SalesController::class, 'index']);                             // Menampilkan halaman awal level user
        Route::post('/list', [SalesController::class, 'list']);                         // menampilkan level user dalam bentuk json untuk datatables
        Route::get('/create', [SalesController::class, 'create']);                      // menampilkan halaman form tambah level user
        Route::post('/', [SalesController::class, 'store']);                            // menyimpan level user baru   
        Route::get('/create_ajax', [SalesController::class, 'create_ajax']);            // menampilkan halaman form tambah user Ajax     
        Route::post('/ajax', [SalesController::class, 'store_ajax']);                   // menyimpan data user baru Ajax
        Route::get('/{id}', [SalesController::class, 'show']);                          // Menampilkan detail level user
        Route::get('/{id}/edit', [SalesController::class, 'edit']);                     // menampilkan halaman form edit level user
        Route::put('/{id}', [SalesController::class, 'update']);                        // menyimpan perubahan level user
        Route::get('/{id}/edit_ajax', [SalesController::class, 'edit_ajax']);           // Menampilkan halaman form edit user Ajax            
        Route::put('/{id}/update_ajax', [SalesController::class, 'update_ajax']);       // Menyimpan perubahan data user Ajax      
        Route::get('/{id}/delete_ajax', [SalesController::class, 'confirm_ajax']);      // Untuk tampilkan form confirm delete user Ajax   
        Route::delete('/{id}/delete_ajax', [SalesController::class, 'delete_ajax']);    // Untuk hapus data user Ajax
        Route::delete('/{id}', [SalesController::class, 'destroy']);                    // menghapus level user
    });
});
