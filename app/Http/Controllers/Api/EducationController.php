<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EducationService;

class EducationController extends Controller
{

    public function get_all_education()
    {
        try {
            $education = EducationService::getAllEducation();
            // dd( $education );
            return $this->sendSuccessResponse([
                'message' => 'Success',
                'education' => $education,
            ]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

}
