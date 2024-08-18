<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {

    use AuthorizesRequests, ValidatesRequests;

    protected function sendErrorResponse( string $message, int $statusCode )
    {
        return response()->json( [ 'error' => $message ], $statusCode );
    }

    protected function sendSuccessResponse( $data, $statusCode = 200) {
        return response()->json( $data, $statusCode );
    }
}
