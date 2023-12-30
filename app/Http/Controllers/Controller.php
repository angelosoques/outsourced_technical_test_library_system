<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
        
    /**
     * Method createResponse
     *
     * @param $message $message [explicite description]
     * @param $code $code [explicite description]
     * @param $data $data [explicite description]
     *
     * @return void
     */
    public function createResponse($message, $code, $data = null) : jsonResponse
    {
        if ($code === 404 || $code === 500) {
            return response()->json(["error" => $message], $code);
        }

        return response()->json(["message" => $message, "data" => $data], $code);
    }
}
