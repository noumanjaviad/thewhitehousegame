<?php

namespace App\Http\Controllers\Api;

use App\Services\DropdownService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function getAllDetails()
    {
        try {
            $dropdownData = DropdownService::getAllData();
            return $this->sendSuccessResponse([
                'message' => 'Success',
                'data' => $dropdownData,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
}
