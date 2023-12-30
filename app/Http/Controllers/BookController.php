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
    public function getAllBooks($libraryId = null)
    {
        if ($libraryId) {
            $book = Book::where('library_id', $libraryId)->get();
        } else {
            $book = Book::all();
        }
        
        return $book->isEmpty() ? PARENT::createResponse('Book not found', 404) : PARENT::createResponse('success', 200, BookResource::collection($book));
    }

    public function getSpecificBook($id)
    {
        $book = Book::find($id);

        return $book->isEmpty() ? PARENT::createResponse('Book not found', 404) : PARENT::createResponse('success', 200, BookResource::collection($book));
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
    
            return $book ? PARENT::createResponse('Book inserted successfully', 201, new BookResource($book)) : PARENT::createResponse('Book insertion failed', 500);
        }
        catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Book insertion failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createResponse($message, 500);
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

            return PARENT::createResponse('Book updated successfully', 201, new BookResource($book));
        }
        catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Book insertion failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createResponse($message, 500);
        }
    }
}
