<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryController;
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

Route::middleware(['web'])->group(function () {
    Route::get('/books/{libraryId?}', [BookController::class, 'getAllBooks']);
    Route::get('/book/{id}', [BookController::class, 'getSpecificBook']);
    Route::post('/insert/book', [BookController::class, 'insertBook']);
    Route::post('/update/book', [BookController::class, 'updateBook']);
});

Route::middleware(['web'])->group(function () {
    Route::get('/library/{libraryId?}', [LibraryController::class, 'getLibrary']);
    Route::post('/insert/library', [LibraryController::class, 'insertLibrary']);
    Route::post('/update/library', [LibraryController::class, 'updateLibrary']);
});

