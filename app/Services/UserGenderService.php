<?php
namespace app\Services;

use App\Models\UserGender;

class UserGenderService
{
    public static function getAllUserGenders()
    {
        return UserGender::select('id', 'name')->get();
    }
}
