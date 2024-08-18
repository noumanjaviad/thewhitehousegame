<?php
namespace app\Services;

use App\Models\Education;

class EducationService
{
    public static function getAllEducation()
    {
        return Education::select('id', 'name')->get();
    }
}
