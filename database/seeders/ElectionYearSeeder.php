<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ElectionYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [1996,1997,1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020, 2021, 2022,2023,2024,2025,2026,2027,2028]; // Add more years if needed

        foreach ($years as $year) {
            DB::table('election_years')->insert([
                'year' => $year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
