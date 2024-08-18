<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserEthnicitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ethnicities = [
            'American Indian', 'Asian/East Asian', 'Black/African American', 'European/White', 'Hispanic/Latino'
            // Add more ethnicities if needed
        ];

        foreach ($ethnicities as $ethnicity) {
            DB::table('user_ethnicities')->insert([
                'name' => $ethnicity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
