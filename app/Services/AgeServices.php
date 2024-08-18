<?php
namespace app\Services;

use App\Models\UserAge;

class AgeServices
{
    public static function getAllAge(){
        return UserAge::select('id','range')->get();
    }
}
