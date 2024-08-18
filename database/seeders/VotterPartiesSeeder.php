<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VotterPartiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partyNames = [
            'Democratic',
            'Republican',
            "Independent('Kennedy')",
            'Other',
        ];

        foreach ($partyNames as $partyName) {
            DB::table('votter_parties')->insert(['party_name' => $partyName]);
        }
    }
}
