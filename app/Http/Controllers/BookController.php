<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Rules\BookRules\checkIfUniqueISBN;
use Exception;
use Illuminate\Http\Request;
use ParentIterator;

class BookController extends Controller
{        
    /**
     * Method getAllBooks
     *
     * @param int $libraryId [explicite description]
     *
     * @return void
     */
    public function getAllBooks(int $libraryId)
    {
        $book = Book::where('library_id', $libraryId)->get();
        return $book->isEmpty() ? $this->createResponse('Book not found', 404) : $this->createResponse('success', 200, new BookResource($book));
    }
    
    /**
     * Method getSpecificBook
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function getSpecificBook(int $id)
    {
        $book = Book::find($id);

        return $book === null ? $this->createResponse('Book not found', 404) : $this->createResponse('success', 200, new BookResource($book));
    }

    public function insertBook(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'ISBN'             => 'required|integer|digits_between:10,13|unique:books,ISBN,NULL,id,library_id,' . $request->input('library_id'),
                'book_title'       => 'required|string|max:50',
                'book_author'      => 'required|string|max:50',
                'number_of_copies' => 'required|integer|digits_between:0,11',
                'library_id'       => 'required|integer|exists:libraries,id'
            ]);
    
            $book = Book::create($validatedData);
    
            return $book ? $this->createResponse('Book inserted successfully', 201, new BookResource($book)) : $this->createResponse('Book insertion failed', 500);
        }
        catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Book insertion failed';
            } else {
                $message = $e->getMessage();
            }
            return $this->createResponse($message, 500);
        }
    }

    public function updateBook(Request $request) {
        try {
            $validatedData = $request->validate([
                'id'               => 'required|integer|exists:books,id',
                'ISBN'             => 'sometimes|integer|digits_between:10,13|unique:books,ISBN,' . $request->input('id') . ',id,library_id,' . $request->input('library_id'),
                'book_title'       => 'sometimes|string|max:50',
                'book_author'      => 'sometimes|string|max:50',
                'number_of_copies' => 'sometimes|integer|digits_between:0,11'
            ]);
    
            $book = Book::find($validatedData['id']);
            
            array_key_exists('ISBN', $validatedData) ? $book->ISBN = $validatedData['ISBN'] : "";
            array_key_exists('book_title', $validatedData) ? $book->book_title = $validatedData['book_title'] : "";
            array_key_exists('book_author', $validatedData) ? $book->book_author = $validatedData['book_author'] : "";
            array_key_exists('number_of_copies', $validatedData) ? $book->number_of_copies = $validatedData['number_of_copies'] : "";

            $book->save();

            return $this->createResponse('Book updated successfully', 201, new BookResource($book));
        }
        catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Book insertion failed';
            } else {
                $message = $e->getMessage();
            }
            return $this->createResponse($message, 500);
        }
    }
}
