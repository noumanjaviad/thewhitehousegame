<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AgeServices;

class UserAgeController extends Controller
{
    public function get_user_age()
    {
        try {
            $user_age = AgeServices::getAllAge();

            return $this->sendSuccessResponse([
                'message' => 'Sucess',
                'user_age' => $user_age,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }
}
