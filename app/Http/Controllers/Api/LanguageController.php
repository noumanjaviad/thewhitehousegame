<?php

namespace App\Http\Controllers\Api;

use App\Models\Language;
use App\Services\LanguageService;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function get_all_language()
    {
        // dd(12);
        try {
            $language = LanguageService::getAllLanguage();
            return $this->sendSuccessResponse([
                'message' => 'Success',
                'language' => $language,
            ]);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }
}
