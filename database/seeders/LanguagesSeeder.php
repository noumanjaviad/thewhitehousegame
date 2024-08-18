<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $languages = [
        //     'Arabic',
        //     'Bangali',
        //     'Cartonese',
        //     'Chinese Mandarin',
        //     'Czech',
        // ];

        $languages = [
            'Arabic',
            'Bengali',
            'Cantonese',
            'Chinese Mandarin',
            'Czech',
            'Danish',
            'Dutch',
            'English',
            'Farsi',
            'French',
            'German',
            'Greek',
            'Gujarati',
            'Hindi',
            'Hungarian',
            'Indonesian',
            'Italian',
            'Igbo',
            'Japanese',
            'Kannada',
            'Khmer',
            'Korean',
            'Mandarin Chinese',
            'Marathi',
            'Mongolian',
            'Native American language',
            'Pashto',
            'Polish',
            'Portuguese',
            'Punjabi',
            'Romanian',
            'Russian',
            'Serbian',
            'Spanish',
            'Swahili',
            'Swedish',
            'Tagalog',
            'Tamil',
            'Telugu',
            'Thai',
            'Turkish',
            'Urdu',
            'Vietnamese',
            'Yoruba',
            'Other',
        ];
        

        foreach ($languages as $language) {
            DB::table('languages')->insert(['name' => $language]);
        }
    }
}
