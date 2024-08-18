<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserEmployementService;

class UserEmployementController extends Controller
{
    public function get_all_user_employement()
    {
        try {
            $user_employement = UserEmployementService::getAllUserEmployement();
            return $this->sendSuccessResponse([
                'message' => 'Sucess',
                'user_employement' => $user_employement,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }
}
