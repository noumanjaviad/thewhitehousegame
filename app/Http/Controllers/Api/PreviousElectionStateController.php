<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\PreviousElectionState;
use App\Models\User;
use Illuminate\Http\Request;

class PreviousElectionStateController extends Controller
{
    public function createPreviousElectionState(Request $request)
    {
        try {
            $previous_election_state = PreviousElectionState::create([
                'votter_party_id' => $request->votter_party_id,
                'election_year' => $request->election_year,
                // 'gender' => $request->gender,
                // 'age_range' => $request->age_range,
                'vote_percentage' => $request->vote_percentage,
                'user_state_id' => $request->user_state_id,
                // 'male_ratio'=>$request->male_ratio,
                // 'female_ratio'=>$request->female_ratio,

            ]);
            $response = [
                'message' => 'previous election create successfully',
                'privous_election_state' => $previous_election_state,
            ];
            return $this->sendSuccessResponse($response, 200);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }

    public function getVotesPercentagePresentYear()
    {
        try {
            $votes2024 = Prediction::whereYear('created_at', 2024)->with('votter_party')->get();
            // dd($votes2024);
            $voteCounts2024 = [];

            // Count the votes for each party in 2024
            foreach ($votes2024 as $vote) {
                $partyId = $vote->votter_party_id;
                if (!isset($voteCounts2024[$partyId])) {
                    $voteCounts2024[$partyId] = 0;
                }
                $voteCounts2024[$partyId]++;
            }

            // Calculate the total number of votes cast in 2024
            $totalVotes2024 = $votes2024->count();

            // Calculate the percentage of votes for each party in 2024
            $percentageVotes2024 = [];
            foreach ($voteCounts2024 as $partyId => $count) {
                $percentage = ($count / $totalVotes2024) * 100;
                $vote = $votes2024->where('votter_party_id', $partyId)->first();
                // dd($vote);
                $partyName = $vote->votter_party->party_name;
                $partyBadge = $vote->votter_party->party_badge;
                $percentageVotes2024[] = [
                    'party_id' => $partyId,
                    'party_name' => $partyName,
                    'party_badge' => $partyBadge,
                    'percentage_2024' => $percentage,
                    'totalVotes' => $totalVotes2024,
                ];
            }

            //percentage of votes for each party in 2020
            $votes2020 = PreviousElectionState::with('votter_party')
                ->where('election_year', 2020)->where('user_state_id', null)
                ->get(['vote_percentage', 'votter_party_id']);
            $percentageVotes2020 = [];

            // Format the data for 2020
            foreach ($votes2020 as $vote) {
                $percentageVotes2020[] = [
                    'party_id' => $vote->votter_party_id,
                    'vote_percentage_2020' => $vote->vote_percentage,
                    'party_name' => $vote->votter_party->party_name,
                    'party_badge' => $vote->votter_party->party_badge,
                ];
            }

            // Find the difference in percentage of votes between 2020 and 2024 for each party
            $comparison = [];
            foreach ($percentageVotes2020 as $vote2020) {
                foreach ($percentageVotes2024 as $vote2024) {
                    if ($vote2020['party_id'] == $vote2024['party_id']) {
                        $comparison[] = [
                            'party_id' => $vote2020['party_id'],
                            'party_name' => $vote2020['party_name'],
                            'party_badge' => $vote2020['party_badge'],
                            'percentage_2020' => $vote2020['vote_percentage_2020'],
                            'percentage_2024' => $vote2024['percentage_2024'],
                            'difference' => $vote2024['percentage_2024'] - $vote2020['vote_percentage_2020'],
                        ];
                        break; //break the inner loop after finding the match
                    }
                }
            }

            // Calculate source voting percentages
            $source_vote = $this->calculateVotingPercentages();

            // Prepare the response
            $response = [
                'message' => 'Success',
                'TotalVotes' => $totalVotes2024, // Use $totalVotes2024 instead of $vote2024['totalVotes']
                'data' => $comparison,
                'source_percentages' => $source_vote,
            ];

            return $this->sendSuccessResponse($response);

        } catch (Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 500);
        }
    }

    private function calculateVotingPercentages()
    {
        // Filter votes for users who voted in 2020 and voted through polling
        $pollingStationVotes = User::with(['voted_candidates' => function ($query) {
            $query->where('user_candidate_vottings.source', 'polling');
        }, 'voted_candidates.party'])
            ->where('is_votted_2020', 'yes')
            ->whereHas('voted_candidates', function ($query) {
                $query->where('user_candidate_vottings.source', 'polling');
            })
            ->get();

        // Filter votes for users who voted in 2020 and voted through mail
        $bilatPaperVotes = User::with(['voted_candidates' => function ($query) {
            $query->where('user_candidate_vottings.source', 'mail');
        }, 'voted_candidates.party'])
            ->where('is_votted_2020', 'yes')
            ->whereHas('voted_candidates', function ($query) {
                $query->where('user_candidate_vottings.source', 'mail');
            })
            ->get();

        $pollingStationResults = [];
        $bilatPaperResults = [];

        // Process polling station votes
        foreach ($pollingStationVotes as $user) {
            foreach ($user->voted_candidates as $candidate) {
                $party = $candidate->party->party_name;
                $pollingStationResults[$party] = $pollingStationResults[$party] ?? 0;
                $pollingStationResults[$party]++;
            }
        }

        // Process bilateral paper votes
        foreach ($bilatPaperVotes as $user) {
            foreach ($user->voted_candidates as $candidate) {
                $party = $candidate->party->party_name;

                $bilatPaperResults[$party] = $bilatPaperResults[$party] ?? 0;
                $bilatPaperResults[$party]++;
            }
        }

        // dd( $pollingStationResults[$party]++,$bilatPaperResults[$party]++);

        $totalVotes = ($pollingStationResults[$party]++) + ($bilatPaperResults[$party]++);
        $results = [];

        // Calculate percentages for polling station votes
        foreach ($pollingStationResults as $party => $votes) {
            // dd($votes);
            $percentage = ($pollingStationResults[$party]++ / $totalVotes) * 100;
            $results[] = [
                'party' => $party,
                'source' => 'polling',
                'percentage' => $percentage,
            ];
        }

        // Calculate percentages for bilateral paper votes
        foreach ($bilatPaperResults as $party => $votes) {
            $percentage = (count($bilatPaperVotes) / $totalVotes) * 100;
            $results[] = [
                'party' => $party,
                'source' => 'mail',
                'percentage' => $percentage,
            ];
        }
        return $results;
    }

}
