<?php

// Import class Route dari Laravel untuk mendefinisikan routing

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
// Import ItemController untuk menghubungkan route dengan controller
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WelcomeController;

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

Route::get('/hello', [WelcomeController::class, 'hello']);

Route::get('/world', function () {
    return 'World';
});

Route::get('/index', [HomeController::class, 'index']);

Route::get('/about', [AboutController::class, 'about']);

Route::get('/articles/{id}', [ArticleController::class, 'articles']);

Route::get('/user/{Devin}', function ($name) {
    return 'Nama Saya ' . $name;
});

Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return 'Pos ke- ' . $postId . " Komentar ke-: " . $commentId;
});


Route::get('/user/{name?}', function ($name = 'John') {
    return 'Nama saya ' . $name;
});

Route::resource('photos', PhotoController::class);

Route::get('/greeting', [WelcomeController::class, 'greeting']);
