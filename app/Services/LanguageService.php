<?php

namespace app\Services;

use App\Models\Language;

class LanguageService
{
    public static function getAllLanguage()
    {
        $languages = Language::select('id', 'name')->orderBy('name', 'ASC')->get();
        $languagesArray = $languages->toArray();
        $english = null;
        $spanish = null;
        $other = ['id' => null, 'name' => 'Other'];
        foreach ($languagesArray as $key => $language) {
            if ($language['name'] === 'English') {
                $english = $language;
                unset($languagesArray[$key]);
            } elseif ($language['name'] === 'Spanish') {
                $spanish = $language;
                unset($languagesArray[$key]);
            }elseif ($language['name'] === 'Other') {
                unset($languagesArray[$key]);
            }
        }

         // Re-index the array
         $languagesArray = array_values($languagesArray);

        if ($spanish) {
            array_unshift($languagesArray, $spanish);
        }
        if ($english) {
            array_unshift($languagesArray, $english);
        }
         // Add "Other" keyword at the end
         $languagesArray[] = $other;

         

        return $languagesArray;
    }
}
