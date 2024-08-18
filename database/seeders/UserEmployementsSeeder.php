<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserEmployementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employmentStatuses = [
            'Employed full time',
            'Employed part time',
            'Employed casual',
            'Employed full time more than one job',
            'Unemployed',
        ];

        foreach ($employmentStatuses as $status) {
            DB::table('user_employements')->insert(['employement_status' => $status]);
        }
    }
}
