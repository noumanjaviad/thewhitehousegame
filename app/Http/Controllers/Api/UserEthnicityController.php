<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserEthnicityService;

class UserEthnicityController extends Controller
{
    public function get_user_ethnicty()
    {
        try {
            $user_ethnicity = UserEthnicityService::getAllUserEthnicities();
            return $this->sendSuccessResponse([
                'message' => 'Sucess',
                'user_ethnicity' => $user_ethnicity,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }
}
