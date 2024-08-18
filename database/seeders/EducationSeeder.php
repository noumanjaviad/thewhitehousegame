<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            'No High School Diploma',
            'High School Diploma (GED)',
            'Some College',
            'Associate Degree',
            "Bachelor's Degree",
        ];

        foreach ($educationLevels as $level) {
            DB::table('education')->insert(['name' => $level]);
        }
    }
}
