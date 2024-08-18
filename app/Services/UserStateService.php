<?php
namespace app\Services;

use App\Models\UserState;

class UserStateService
{
    public static function getAllStates()
    {
        return UserState::select('id', 'name')->orderByRaw("CASE WHEN name = 'USA' THEN 0 ELSE 1 END, name ASC")
            ->get();

    }

}
