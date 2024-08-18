<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserCountryBirthService;

class UserCountryBirthController extends Controller
{
    public function get_all_user_country_birth()
    {

        try {
            $user_country_birth = UserCountryBirthService::getAllUserCountryBirth();
            return $this->sendSuccessResponse([
                'message' => 'Sucess',
                'user_country_birth' => $user_country_birth,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }
}
