<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use function PHPSTORM_META\map;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    protected function createdResponse($message, $code, $data = null)
    {
        if ($code === 404 || $code === 500) {
            return response()->json(["error" => $message], $code);
        }

        return response()->json(["message" => $message, "data" => $data], $code);
    }
}
