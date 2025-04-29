<?php

// Import class Route dari Laravel untuk mendefinisikan routing
use Illuminate\Support\Facades\Route;
// Import ItemController untuk menghubungkan route dengan controller
use App\Http\Controllers\ItemController;

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
// Route untuk halaman utama ('/') yang menampilkan view welcome
Route::get('/', function () {
    return view('welcome'); // Tampilkan halaman welcome
});

// Route resource untuk ItemController
// Ini otomatis membuat route untuk CRUD (Create, Read, Update, Delete) item
Route::resource('items', ItemController::class);