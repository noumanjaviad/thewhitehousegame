<?php
namespace app\Services;

use App\Models\UserEthnicity;

class UserEthnicityService
{
    public static function getAllUserEthnicities(){
        return UserEthnicity::select('id','name')->get();
    }
}
