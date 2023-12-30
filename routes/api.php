<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LibraryController;

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
//library
Route::post('/insert/library', [LibraryController::class, 'insertLibrary']);
Route::post('/update/library', [LibraryController::class, 'updateLibrary']);

Route::get('/book/{id}', [BookController::class, 'getSpecificBook']);
Route::post('/insert/book', [BookController::class, 'insertBook']);
Route::post('/update/book', [BookController::class, 'updateBook']);

Route::get('/books/{libraryId}', [BookController::class, 'getAllBooks']);