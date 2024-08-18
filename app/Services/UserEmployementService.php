<?php
namespace app\Services;

use App\Models\UserEmployement;

class UserEmployementService
{
    public static function getAllUserEmployement(){
        return UserEmployement::select('id','employement_status')->get();
    }
}
