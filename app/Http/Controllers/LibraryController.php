<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library;
use App\Http\Resources\LibraryResource;
use Exception;

class LibraryController extends Controller
{
    public function getLibrary(int $id)
    {
        $library = Library::find($id);

        return $library === null ? PARENT::createResponse('Library not found', 404) : PARENT::createResponse('success', 200, LibraryResource::collection($library));
    }

    public function insertLibrary(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'library_name'    => 'required|string|max:50',
                'library_address' => 'required|string|max:225',
            ]);

            $library = Library::create($validatedData);

            return PARENT::createResponse('Library updated successfully', 201, new LibraryResource($library));
        }
        catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Library insertion failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createResponse($message, 500);
        }
    }

    public function updateLibrary(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer|exists:libraries,id',
                'library_name' => 'sometimes|string|max:50',
                'library_address' => 'sometimes|string|max:225',
            ]);

            $library = Library::find($validatedData['id']);

            array_key_exists('library_name', $validatedData) ? $library->library_name = $validatedData['library_name'] : "";
            array_key_exists('library_address', $validatedData) ? $library->library_address = $validatedData['library_address'] : "";

            $library->save();

            return $library ? PARENT::createdResponse('Library updated successfully', 201, new LibraryResource($library)) : PARENT::createdResponse('Library update failed', 500);
        }
        catch (Exception $e) {
            if ($e->getCode() !== 0) {
                $message = 'Library update failed';
            } else {
                $message = $e->getMessage();
            }
            return PARENT::createdResponse($message, 500);
        }
    }
}
