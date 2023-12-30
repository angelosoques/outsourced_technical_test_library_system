<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowBooksController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MemberController;

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
//library routes
Route::post('/insert/library', [LibraryController::class, 'insertLibrary']);
Route::post('/update/library', [LibraryController::class, 'updateLibrary']);

//books routes
Route::get('/book/{id}', [BookController::class, 'getSpecificBook']);
Route::post('/insert/book', [BookController::class, 'insertBook']);
Route::post('/update/book', [BookController::class, 'updateBook']);
Route::get('/books/{libraryId}', [BookController::class, 'getAllBooks']);

//members routes
Route::get('/members/{libraryId}', [MemberController::class], 'getMembers');
Route::get('/member/{id}', [MemberController::class, 'getSpecificMember']);
Route::post('/insert/member', [MemberController::class, 'insertMember']);
Route::post('/update/member', [MemberController::class, 'updateMember']);

//borrowbooks routes
Route::post('/member/borrow', [BorrowBooksController::class, 'insertBorrowBook']);
