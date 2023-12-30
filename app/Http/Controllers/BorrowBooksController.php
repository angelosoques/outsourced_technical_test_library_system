<?php

namespace App\Http\Controllers;

use App\Models\BorrowedBook;
use App\Models\Book;
use App\Http\Resources\BorrowedBookResource;
use App\Rules\matchLibraryId;
use Illuminate\Http\Request;

class BorrowBooksController extends Controller
{
    public function getAllBorrowedBooks($id)
    {
        $books = Book::where('books.library_id', $id)
                        ->selectRaw('books.id, ISBN, book_title, book_author, email_address, books.number_of_copies - COALESCE(SUM(borrowed.book_id),0) as available_copies')
                        ->rightJoin('borrowed_books', function ($joinQuery) {
                            $joinQuery->on('books.id', '=', 'borrowed_books.book_id')
                                    ->where('borrowed_books.returned', '=', 0);
                        })
                        ->join('members', 'members.id', '=', 'borrowed_books.member_id')
                        ->groupBy('books.id', 'books.number_of_copies');

        return $books->isEmpty() ? PARENT::createResponse('Books not found', 404) : PARENT::createResponse('success', 200, BorrowedBookResource::collection($books));
    }

    public function getReturnedOrUnreturnedBooks($returned, $libraryId) 
    {
        $books = BorrowedBook::where("returned", $returned)
                            ->where("books.library_id", $libraryId)
                            ->selectRaw('books.id, ISBN, book_title, book_author, email_address')
                            ->join('books', 'books.id', '=', 'borrowed_books.book_id')
                            ->join('members', 'members.id', '=', 'borrowed_books.member_id');

        return $books->isEmpty() ? PARENT::createResponse('Books not found', 404) : PARENT::createResponse('success', 200, BorrowedBookResource::collection($books));
    }

    public function getBorrowedBooksOfMember($memberid, $libraryId) 
    {
        $books = BorrowedBook::where("member.id", $memberid)
                            ->where("books.library_id", $libraryId)
                            ->selectRaw('member.id, email_address, book.id, ISBN, book_title, book_author')
                            ->join('books', 'books.id', '=', 'borrowed_books.book_id')
                            ->join('members', 'members.id', '=', 'borrowed_books.member_id');

        return $books->isEmpty() ? PARENT::createResponse('Books not found', 404) : PARENT::createResponse('success', 200, BorrowedBookResource::collection($books));
    }

    public function insertBorrowBook(Request $request)
    {
        $validatedData = $request->validate([
            'member_id'  => 'required|integer|exists:members,id',
            'book_id'    => 'required|array',
            'book_id.*'  => 'required|integer|exists:books,id',
            'library_id' => ['required', 'integer', 'exists:libraries,id', new matchLibraryId($request->input('member_id'), $request->input('book_id'))]
        ]);

        $book_ids = $validatedData['book_ids'];

        foreach ($book_ids as $book_id) {
            $book = BorrowedBook::create($validatedData);
            $bookDetails = Book::find($book_id)->select("book_title", "book_author");
            
            $book ? array_push($bookStatus, $bookDetails->book_title . " by " . $bookDetails->book_author . " - success") : array_push($failed, $bookDetails->book_title . " by " . $bookDetails->book_author);
        }

        if (empty($failed) === false) {
            return PARENT::createResponse(implode('\n', $failed) . 'failed', 500);
        }
        return PARENT::createResponse('success', 201, $bookStatus);
    }
}
