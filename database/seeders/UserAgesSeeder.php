<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserAgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ageRanges = [
            'under 16 years old',
            '16-17 years old',
            '18-24 years old',
            '25-34 years old',
            '35-44 years old',
        ];

        foreach ($ageRanges as $range) {
            DB::table('user_ages')->insert(['range' => $range]);
        }
    }
}
