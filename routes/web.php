<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowBooksController;
use App\Http\Controllers\MemberController;
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

Route::get('/books/{libraryId}', function ($id) {
    $bookController = new BookController();
    $data = $bookController->getAllBooks($id);
    
    return view('/library/home', ['data' => $data]);
});

Route::get('/profile/{memberId}', function ($id) {
    $memberController = new MemberController();
    $borrowedBooks = new BorrowBooksController();
    $data = $memberController->getSpecificMember($id);
    $borrowedBooks = $borrowedBooks->getBorrowedBooksOfMember($id);

    return view('/member/profile', ['memberdata' => $data, 'borrowedBooks' => $borrowedBooks]);
});