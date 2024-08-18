<?php
namespace app\Services;

use App\Services\AgeServices;
use App\Services\LanguageService;
use App\Services\EducationService;
use App\Services\UserStateService;
use App\Services\UserGenderService;
use App\Services\UserEthnicityService;
use App\Services\UserEmployementService;
use App\Services\UserCountryBirthService;

class DropdownService
{
    public static function getAllData()
    {
        try {
            $education = EducationService::getAllEducation();
            $language = LanguageService::getAllLanguage();
            $user_age = AgeServices::getAllAge();
            $user_country_birth = UserCountryBirthService::getAllUserCountryBirth();
            $user_employement = UserEmployementService::getAllUserEmployement();
            $user_ethnicity = UserEthnicityService::getAllUserEthnicities();
            $user_gender = UserGenderService::getAllUserGenders();
            $user_state = UserStateService::getAllStates();

            return [
                'education' => $education,
                'language' => $language,
                'user_age' => $user_age,
                'user_country_birth' => $user_country_birth,
                'user_employement' => $user_employement,
                'user_ethnicity' => $user_ethnicity,
                'user_gender' => $user_gender,
                'user_state' => $user_state,
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}