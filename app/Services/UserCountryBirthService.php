<?php
namespace app\Services;

use App\Models\UserCountryBirth;

class UserCountryBirthService
{
    public static function getAllUserCountryBirth()
    {
        return UserCountryBirth::select('id', 'name')
            ->orderByRaw("CASE WHEN name = 'United States of America' THEN 0 ELSE 1 END, name ASC")
            ->get();
    }

}
