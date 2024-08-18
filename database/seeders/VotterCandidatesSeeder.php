<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VoterCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = [
            ['candidate_name' => 'Biden/Harris', 'candidate_image' => 'biden_harris_image.jpg'],
            ['candidate_name' => 'Trump/Pence', 'candidate_image' => 'trump_pence_image.jpg'],
            ['candidate_name' => 'Other', 'candidate_image' => null],
        ];

        foreach ($candidates as $candidate) {
            DB::table('voter_candidates')->insert($candidate);
        }
    }
}
