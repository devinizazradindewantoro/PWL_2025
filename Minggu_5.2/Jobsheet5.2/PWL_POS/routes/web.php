<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
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
//Route 
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);

// Jobsheet 5
Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);              // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);          // menampilkan data user dalam bentuk json untuk datatables 
    Route::get('/create', [UserController::class, 'create']);       // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);             // menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);           // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);      // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);         // menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']);     // menghapus data user
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index'])->name('level.index');                // Menampilkan halaman awal level user
    Route::post('/list', [LevelController::class, 'list'])->name('level.list');             // menampilkan level user dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create'])->name('level.create');        // menampilkan halaman form tambah level user
    Route::post('/', [LevelController::class, 'store'])->name('level.store');               // menyimpan level user baru
    Route::get('/{id}', [LevelController::class, 'show'])->name('level.show');              // Menampilkan detail level user
    Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');         // menampilkan halaman form edit level user
    Route::put('/{id}', [LevelController::class, 'update'])->name('level.update');          // menyimpan perubahan level user
    Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy');     // menghapus level user
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');              // Menampilkan halaman awal daftar kategori
    Route::post('/list', [KategoriController::class, 'list'])->name('kategori.list');           // menampilkan kategori dalam bentuk json untuk datatables
    Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');      // menampilkan halaman form tambah kategori
    Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');             // menyimpan kategori baru
    Route::get('/{id}', [KategoriController::class, 'show'])->name('kategori.show');            // menampilkan detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');       // menampilkan halaman form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');        // menyimpan perubahan kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');   // menghapus kategori
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index'])->name('barang.index');                  // menampilkan halaman awal barang
    Route::post('/list', [BarangController::class, 'list'])->name('barang.list');               // menampilkan data barang dalam bentuk json untuk datatables
    Route::get('/create', [BarangController::class, 'create'])->name('barang.create');          // menampilkan halaman form tambah barang
    Route::post('/', [BarangController::class, 'store'])->name('barang.store');                 // menyimpan data barang baru
    Route::get('/{id}', [BarangController::class, 'show'])->name('barang.show');                // menampilkan detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');           // menampilkan halaman form edit barang
    Route::put('/{id}', [BarangController::class, 'update'])->name('barang.update');            // menyimpan perubahan data barang
    Route::delete('/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');       // menghapus data barang
});

Route::group(['prefix' => 'stok'], function () {
    Route::get('/', [StokController::class, 'index'])->name('stok.index');                      // menampilkan halaman awal supplier
    Route::post('supplier/list', [StokController::class, 'list'])->name('stok.list');           // menampilkan data supplier dalam bentuk json untuk datatables
    Route::get('/create', [StokController::class, 'create'])->name('stok.create');              // menampilkan halaman form tambah supplier
    Route::post('/', [StokController::class, 'store'])->name('stok.store');                     // menyimpan data supplier baru
    Route::get('/{id}', [StokController::class, 'show'])->name('stok.show');                    // menampilkan detail supplier
    Route::get('/{id}/edit', [StokController::class, 'edit'])->name('stok.edit');               // menampilkan halaman form edit supplier
    Route::put('/{id}', [StokController::class, 'update'])->name('stok.update');                // menyimpan perubahan data supplier
    Route::delete('/{id}', [StokController::class, 'destroy'])->name('stok.destroy');           // menghapus data supplier
});
