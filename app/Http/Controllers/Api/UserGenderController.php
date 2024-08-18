<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserGenderService;

class UserGenderController extends Controller
{
    public function get_user_gender()
    {
        try {

            $user_gender = UserGenderService::getAllUserGenders();
            // dd($user_gender);
            return $this->sendSuccessResponse([
                'message' => 'Sucess',
                'user_gender' => $user_gender,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }
}
