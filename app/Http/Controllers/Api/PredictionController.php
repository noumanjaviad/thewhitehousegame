<?php

namespace App\Http\Controllers\Api;

// use auth;
use App\Models\User;
use App\Models\UserState;
use App\Models\Prediction;
use App\Models\VotterParty;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VoterCandidate;
use Illuminate\Support\Facades\DB;
use App\Models\UserPredictionParty;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ChosenPartyCandidate;
use Illuminate\Support\Facades\Auth;
use App\Models\PreviousElectionState;
use App\Http\Requests\SelectPartyLeadersRequest;
use App\Http\Requests\GetPredictedPartyCandidate;
use App\Models\ChosenPresidentVicePresidentStates;
use App\Http\Requests\ElectoralCollegePredictionRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PredictionController extends Controller
{
    //selecting chosen candidate  before submitting the Prediction
    // public function chosePartyLeaders(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         $user_id = $user->id;

    //         $predictions = [];

    //         foreach ($request->parties as $party) {
    //             $presidentCandidate = VoterCandidate::findOrFail($party['president_id']);
    //             $vicePresidentCandidate = VoterCandidate::findOrFail($party['vice_president_id']);

    //             // Update the Prediction for president
    //             $predictionPresident = ChosenPartyCandidate::updateOrCreate(
    //                 [
    //                     'user_id' => $user_id,
    //                     'votter_party_id' => $party['votter_party_id'],
    //                     'position' => 'president',
    //                 ],
    //                 [
    //                     'voter_candidate_id' => $party['president_id'],
    //                 ]
    //             );

    //             // Update the Prediction for vice president
    //             $predictionVicePresident = ChosenPartyCandidate::updateOrCreate(
    //                 [
    //                     'user_id' => $user_id,
    //                     'votter_party_id' => $party['votter_party_id'],
    //                     'position' => 'vice_president',
    //                 ],
    //                 [
    //                     'voter_candidate_id' => $party['vice_president_id'],
    //                 ]
    //             );

    //             $predictions[] = [
    //                 'party_id' => $party['votter_party_id'],
    //                 'prediction_president' => $predictionPresident,
    //                 'prediction_vice_president' => $predictionVicePresident,
    //             ];
    //         }

    //         $response = [
    //             'message' => 'President and vice president updated successfully',
    //             'predictions' => $predictions,
    //         ];
    //         return $this->sendSuccessResponse($response);
    //     } catch (Exception $e) {
    //         return $this->sendErrorResponse('Internal server error', 500);
    //     }
    // }
    public function chosePartyLeaders(Request $request)
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;

            $predictions = [];

            foreach ($request->parties as $party) {
                $presidentCandidateExists = VoterCandidate::where('id', $party['president_id'])->exists();
                $vicePresidentCandidateExists = VoterCandidate::where('id', $party['vice_president_id'])->exists();

                if (!$presidentCandidateExists) {
                    // Check in candidate_party pivot table
                    $presidentCandidateExistsInPivot = \DB::table('candidate_party')
                        ->where('voter_candidate_id', $party['president_id'])
                        ->where('voter_party_id', $party['votter_party_id'])
                        ->exists();

                    if (!$presidentCandidateExistsInPivot) {
                        return $this->sendErrorResponse("President candidate does not belong to the specified party", 400);
                    }
                }

                if (!$vicePresidentCandidateExists) {
                    // Check in candidate_party pivot table
                    $vicePresidentCandidateExistsInPivot = \DB::table('candidate_party')
                        ->where('voter_candidate_id', $party['vice_president_id'])
                        ->where('voter_party_id', $party['votter_party_id'])
                        ->exists();

                    if (!$vicePresidentCandidateExistsInPivot) {
                        return $this->sendErrorResponse("Vice president candidate does not belong to the specified party", 400);
                    }
                }

                // Update or create the Prediction for president
                $predictionPresident = ChosenPartyCandidate::updateOrCreate(
                    [
                        'user_id' => $user_id,
                        'votter_party_id' => $party['votter_party_id'],
                        'position' => 'president',
                    ],
                    [
                        'voter_candidate_id' => $party['president_id'],
                    ]
                );

                // Update or create the Prediction for vice president
                $predictionVicePresident = ChosenPartyCandidate::updateOrCreate(
                    [
                        'user_id' => $user_id,
                        'votter_party_id' => $party['votter_party_id'],
                        'position' => 'vice_president',
                    ],
                    [
                        'voter_candidate_id' => $party['vice_president_id'],
                    ]
                );

                $predictions[] = [
                    'party_id' => $party['votter_party_id'],
                    'prediction_president' => $predictionPresident,
                    'prediction_vice_president' => $predictionVicePresident,
                ];
            }

            $response = [
                'message' => 'President and vice president updated successfully',
                'predictions' => $predictions,
            ];
            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
    //end

    //this api for predict party leader who will won
    // public function predictPartyLeaders(SelectPartyLeadersRequest $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $user = Auth::user();

    //         $user_id = $user->id;
    //         // dd($user_id);

    //         // Check if the user has made a payment
    //         if (empty($user->stripe_customer_id)) {
    //             // Delete chosen candidates from chosen_party_candidates
    //             ChosenPartyCandidate::where('user_id', $user_id)->delete();
    //             DB::commit(); // Commit the transaction before returning
    //             return $this->sendErrorResponse('Payment required to submit Prediction', 403);
    //         }

    //         // Ensure that the president and vice president belong to the specified party
    //         $president = VoterCandidate::findOrFail($request->president_id);
    //         $vicePresident = VoterCandidate::findOrFail($request->vice_president_id);

    //         // Delete any existing predictions for the current user and party
    //         Prediction::where('user_id', $user_id)->delete();

    //         // Create new Prediction for president
    //         $predictionPresident = Prediction::updateOrCreate([
    //             'user_id' => $user_id,
    //             'votter_party_id' => $request->votter_party_id,
    //             'voter_candidate_id' => $request->president_id,
    //             'position' => 'president',
    //         ]);

    //         // Create new Prediction for vice president
    //         $predictionVicePresident = Prediction::updateOrCreate([
    //             'user_id' => $user_id,
    //             'votter_party_id' => $request->votter_party_id,
    //             'voter_candidate_id' => $request->vice_president_id,
    //             'position' => 'vice_president',
    //         ]);
    //         DB::commit();

    //         $response = [
    //             'message' => 'President and vice president chosen successfully',
    //             'prediction_president' => $predictionPresident,
    //             'prediction_vice_president' => $predictionVicePresident,
    //         ];

    //         return $this->sendSuccessResponse($response);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         //    ChosenPartyCandidate::where('user_id', $user->id)->delete();
    //         //    dd($test);
    //         return $this->sendErrorResponse('Internal server error', 500);
    //     }
    // }

    public function predictPartyLeaders(SelectPartyLeadersRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user_id = $user->id;

            // Check if the user has made a payment
            if (empty($user->stripe_customer_id)) {
                // Delete chosen candidates from chosen_party_candidates
                ChosenPartyCandidate::where('user_id', $user_id)->delete();
                DB::commit(); // Commit the transaction before returning
                return $this->sendErrorResponse('Payment required to submit Prediction', 403);
            }

            // Ensure that the president and vice president belong to the specified party
            $presidentCandidateExists = VoterCandidate::where('id', $request->president_id)->exists();
            $vicePresidentCandidateExists = VoterCandidate::where('id', $request->vice_president_id)->exists();

            if (!$presidentCandidateExists) {
                // Check in candidate_party pivot table
                $presidentCandidateExistsInPivot = \DB::table('candidate_party')
                    ->where('voter_candidate_id', $request->president_id)
                    ->where('votter_party_id', $request->votter_party_id)
                    ->exists();

                if (!$presidentCandidateExistsInPivot) {
                    return $this->sendErrorResponse("President candidate does not belong to the specified party", 400);
                }
            }

            if (!$vicePresidentCandidateExists) {
                // Check in candidate_party pivot table
                $vicePresidentCandidateExistsInPivot = \DB::table('candidate_party')
                    ->where('voter_candidate_id', $request->vice_president_id)
                    ->where('votter_party_id', $request->votter_party_id)
                    ->exists();

                if (!$vicePresidentCandidateExistsInPivot) {
                    return $this->sendErrorResponse("Vice president candidate does not belong to the specified party", 400);
                }
            }

            // Delete any existing predictions for the current user and party
            Prediction::where('user_id', $user_id)->delete();

            // Create new Prediction for president
            $predictionPresident = Prediction::updateOrCreate([
                'user_id' => $user_id,
                'votter_party_id' => $request->votter_party_id,
                'voter_candidate_id' => $request->president_id,
                'position' => 'president',
            ]);

            // Create new Prediction for vice president
            $predictionVicePresident = Prediction::updateOrCreate([
                'user_id' => $user_id,
                'votter_party_id' => $request->votter_party_id,
                'voter_candidate_id' => $request->vice_president_id,
                'position' => 'vice_president',
            ]);

            DB::commit();

            $response = [
                'message' => 'President and vice president chosen successfully',
                'prediction_president' => $predictionPresident,
                'prediction_vice_president' => $predictionVicePresident,
            ];

            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    //end

    //this is for mobile without pay
    public function predictPartyLeadersApp(SelectPartyLeadersRequest $request)
    {
        // dd(12);
        // DB::beginTransaction();
        try {
            $user = Auth::user();
            $user_id = $user->id;

            // Check if the user has made a payment
            // if (empty($user->stripe_customer_id)) {
            //     // Delete chosen candidates from chosen_party_candidates
            //     ChosenPartyCandidate::where('user_id', $user_id)->delete();
            //     DB::commit(); // Commit the transaction before returning
            //     return $this->sendErrorResponse('Payment required to submit Prediction', 403);
            // }

            // Ensure that the president and vice president belong to the specified party
            $presidentCandidateExists = VoterCandidate::where('id', $request->president_id)->exists();
            $vicePresidentCandidateExists = VoterCandidate::where('id', $request->vice_president_id)->exists();
            // dd($vicePresidentCandidateExists,$presidentCandidateExists);

            if (!$presidentCandidateExists) {
                // dd(12);
                // Check in candidate_party pivot table
                $presidentCandidateExistsInPivot = \DB::table('candidate_party')
                    ->where('voter_candidate_id', $request->president_id)
                    ->where('votter_party_id', $request->votter_party_id)
                    ->exists();

                if (!$presidentCandidateExistsInPivot) {
                    return $this->sendErrorResponse("President candidate does not belong to the specified party", 400);
                }
            }

            if (!$vicePresidentCandidateExists) {
                // dd(12);
                // Check in candidate_party pivot table
                $vicePresidentCandidateExistsInPivot = \DB::table('candidate_party')
                    ->where('voter_candidate_id', $request->vice_president_id)
                    ->where('votter_party_id', $request->votter_party_id)
                    ->exists();

                if (!$vicePresidentCandidateExistsInPivot) {
                    return $this->sendErrorResponse("Vice president candidate does not belong to the specified party", 400);
                }
            }

            // Delete any existing predictions for the current user and party
            Prediction::where('user_id', $user_id)->delete();

            // Create new Prediction for president
            $predictionPresident = Prediction::updateOrCreate([
                'user_id' => $user_id,
                'votter_party_id' => $request->votter_party_id,
                'voter_candidate_id' => $request->president_id,
                'position' => 'president',
            ]);

            // Create new Prediction for vice president
            $predictionVicePresident = Prediction::updateOrCreate([
                'user_id' => $user_id,
                'votter_party_id' => $request->votter_party_id,
                'voter_candidate_id' => $request->vice_president_id,
                'position' => 'vice_president',
            ]);

            // dd($predictionPresident,$predictionVicePresident);

            // DB::commit();

            $response = [
                'message' => 'President and vice president chosen successfully',
                'prediction_president' => $predictionPresident,
                'prediction_vice_president' => $predictionVicePresident,
            ];

            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            // DB::rollBack();
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
    //end function

    // public function submitElectoralCollegePrediction(ElectoralCollegePredictionRequest $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         // $user = auth()->user();

    //         $user = Auth::user();
    //         // $user_id = $user->id;

    //         // Check if the user has made a payment
    //         if (empty($user->stripe_customer_id)) {
    //             return $this->sendErrorResponse('Payment required to submit Prediction', 403);
    //         }

    //         foreach ($request->state_predictions as $prediction) {
    //             $UserPredictionParty = new UserPredictionParty();
    //             $UserPredictionParty->user_id = $user->id;
    //             $UserPredictionParty->votter_party_id = $prediction['party_id'];
    //             $UserPredictionParty->user_state_id = $prediction['state_id'];
    //             $UserPredictionParty->save();

    //         }
    //         DB::commit();

    //         $response = [
    //             'message' => 'Predictions submitted successfully',
    //         ];
    //         // return response()->json( [ 'message' => 'Predictions submitted successfully' ] );
    //         return $this->sendSuccessResponse($response);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return $this->sendErrorResponse('internal server error', 500);
    //     }
    // }

    public function submitElectoralCollegePrediction(ElectoralCollegePredictionRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user_id = $user->id;
            // dd($user->id);

            if (empty($user->stripe_customer_id)) {
                ChosenPartyCandidate::where('user_id', $user_id)->delete();
                DB::commit();
                return $this->sendErrorResponse('Payment required to submit Prediction', 403);
            }

            foreach ($request->state_predictions as $prediction) {
                // dd($prediction);
                $stateId = $prediction['state_id'];
                $partyId = $prediction['party_id'];
                // dd($stateId, $partyId);

                $existingPrediction = UserPredictionParty::where('user_id', $user->id)
                    ->where('user_state_id', $stateId)
                    ->first();
                // dd($existingPrediction);
                if ($existingPrediction) {
                    // Update existing Prediction
                    $existingPrediction->votter_party_id = $partyId;
                    $existingPrediction->save();
                } else {
                    // Create new Prediction
                    $newPrediction = new UserPredictionParty();
                    $newPrediction->user_id = $user->id;
                    $newPrediction->votter_party_id = $partyId;
                    $newPrediction->user_state_id = $stateId;
                    $newPrediction->save();
                }
            }

            DB::commit();

            // $finalizedCandidates = ChosenPresidentVicePresidentStates::with('voted_candidates', 'party')->where('user_id', auth()->id())->get();
            $finalizedCandidates = $this->test1();
            // dd($finalizedCandidates);

            $response = [
                'message' => 'Predictions submitted successfully',
                'finalizedCandidates' => $finalizedCandidates,
            ];
            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    private function test1()
    {
        $stateElections = UserPredictionParty::where('user_id', auth()->id())->with('party', 'state')->get();
        $partyCountsByState = [];
        $electoralVotesByParty = [];

        // Collect vote counts by state and party
        foreach ($stateElections as $stateElection) {
            if ($stateElection->state) {
                $stateName = $stateElection->state->name;
                $partyName = $stateElection->party->party_name;
                $stateFlag = $stateElection->state->state_image_url;
                $stateMap = $stateElection->state->image_url;
                $electoralCollage = $stateElection->state->electrical_collage_number;
                $electoralCollage_1 = $stateElection->state->electrical_collage_number_1;

                // Initialize state entry if it doesn't exist
                if (!isset($partyCountsByState[$stateName])) {
                    $partyCountsByState[$stateName] = [
                        'map_url' => $stateMap,
                        'state_image_url' => $stateFlag,
                        'electrical_collage' => $electoralCollage,
                        'electrical_collage_1' => $electoralCollage_1,
                        'parties' => [],
                    ];
                }

                // Initialize party count if it doesn't exist
                if (!isset($partyCountsByState[$stateName]['parties'][$partyName])) {
                    $partyCountsByState[$stateName]['parties'][$partyName] = 0;
                }

                // Increment the party count for the state
                $partyCountsByState[$stateName]['parties'][$partyName]++;
            }
        }

        // Calculate percentages and find the winning party
        foreach ($partyCountsByState as $stateName => &$stateData) {
            $totalVotes = array_sum($stateData['parties']);
            $maxPercentage = 0;
            $winningParty = null;

            foreach ($stateData['parties'] as $partyName => &$votes) {
                $votes = ($votes / $totalVotes) * 100;

                if ($votes > $maxPercentage) {
                    $maxPercentage = $votes;
                    $winningParty = $partyName;
                }
            }

            $stateData['winning_party'] = $winningParty;

            // Update the electoral votes for the winning party
            if ($winningParty) {
                if (!isset($electoralVotesByParty[$winningParty])) {
                    $electoralVotesByParty[$winningParty] = 0;
                }
                $electoralVotesByParty[$winningParty] += $stateData['electrical_collage_1'];
            }
        }

        $winningCandidates = [];
        $candidates = [];
        $presidentCandidate = null;
        $vicePresidentCandidate = null;

        $highestElectoralVotes = 0;
        $chosenParty = null;

// Find the party with the highest electoral votes among the specified parties
        foreach ($electoralVotesByParty as $partyName => $electoralVotes) {
            if ($partyName === 'Democratic' || $partyName === 'Republican' || $partyName === "Independent('Kennedy')") {
                if ($electoralVotes > $highestElectoralVotes) {
                    $highestElectoralVotes = $electoralVotes;
                    $chosenParty = $partyName;
                }
            }
        }
        // dd($highestElectoralVotes,$chosenParty);
        if ($chosenParty || $highestElectoralVotes > 270) {
            $candidates = ChosenPartyCandidate::whereHas('votter_party', function ($query) use ($chosenParty) {
                $query->where('party_name', $chosenParty);
            })
                ->where('user_id', auth()->id())
                ->whereIn('position', ['president', 'vice_president'])
                ->with('votter_party', 'voter_candidate')
                ->get();
            $groupedCandidates = $candidates->groupBy('position')->map(function ($item) {
                return $item->first();
            });
            $presidentCandidate = $groupedCandidates->get('president', null);
            $vicePresidentCandidate = $groupedCandidates->get('vice_president', null);
            // $this->test($presidentCandidate, $vicePresidentCandidate);
            $winningCandidates = [
                'president' => $presidentCandidate,
                'vice_president' => $vicePresidentCandidate,
            ];

            return $winningCandidates;
        }
    }

    //for mobile app without payment
    public function submitElectoralCollegePredictionApp(ElectoralCollegePredictionRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            // dd($user->id);

            // if (empty($user->stripe_customer_id)) {
            //     return $this->sendErrorResponse('Payment required to submit Prediction', 403);
            // }

            foreach ($request->state_predictions as $prediction) {
                // dd($prediction);
                $stateId = $prediction['state_id'];
                $partyId = $prediction['party_id'];
                // dd($stateId, $partyId);

                $existingPrediction = UserPredictionParty::where('user_id', $user->id)
                    ->where('user_state_id', $stateId)
                    ->first();
                // dd($existingPrediction);
                if ($existingPrediction) {
                    // Update existing Prediction
                    $existingPrediction->votter_party_id = $partyId;
                    $existingPrediction->save();
                } else {
                    // Create new Prediction
                    $newPrediction = new UserPredictionParty();
                    $newPrediction->user_id = $user->id;
                    $newPrediction->votter_party_id = $partyId;
                    $newPrediction->user_state_id = $stateId;
                    $newPrediction->save();
                }
            }

            DB::commit();

            $response = [
                'message' => 'Predictions submitted successfully',
            ];
            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }
    //end function

    private function StoreFinalizeCandidateElectroral()
    {
        // dd(12);
        $stateElections = UserPredictionParty::with('party', 'state')->get();
        // Initialize an empty array to hold the counts
        $partyCountsByState = [];
        $electoralVotesByParty = [];

        // Loop through each state election
        foreach ($stateElections as $stateElection) {
            // dd($stateElection);
            // Check if the state relationship exists
            if ($stateElection->state) {
                $stateName = $stateElection->state->name;
                $partyName = $stateElection->party->party_name;
                $stateFlag = $stateElection->state->state_image_url;
                $stateMap = $stateElection->state->image_url;

                $electoralCollage = $stateElection->state->electrical_collage_number;

                // If the state id doesn't exist in the array, initialize it
                if (!isset($partyCountsByState[$stateName])) {
                    $partyCountsByState[$stateName] = [
                        'map_url' => $stateMap,
                        'state_image_url' => $stateFlag,
                        'electrical_collage' => $electoralCollage,
                        'winning_party' => null,
                    ];
                }

                // If the party name doesn't exist in the state's count, initialize it
                if (!isset($partyCountsByState[$stateName][$partyName])) {
                    $partyCountsByState[$stateName][$partyName] = 0;
                }

                // Increment the count for the party in the state
                $partyCountsByState[$stateName][$partyName]++;
            }
        }

        // Calculate percentages and find the winning party for each state
        foreach ($partyCountsByState as $stateName => &$state) {
            // Exclude 'state_image_url' and 'electrical_collage' from the total votes
            $totalVotes = array_sum(array_diff_key($state, ['state_image_url' => true, 'map_url' => true, 'electrical_collage' => true]));

            // Calculate percentage for each party
            foreach ($state as $key => &$count) {
                if ($key !== 'state_image_url' && $key !== 'map_url' && $key !== 'electrical_collage') {
                    $count = ($count / $totalVotes) * 100; // Calculate percentage
                }
            }

            // Find the winning party
            $winningParty = null;
            $maxPercentage = 0;
            foreach ($state as $key => $percentage) {
                if ($key !== 'state_image_url' && $key !== 'map_url' && $key !== 'electrical_collage' && $percentage > $maxPercentage) {
                    $maxPercentage = $percentage;
                    $winningParty = $key;
                }
            }
            $state['winning_party'] = $winningParty;

            // Update the electoral votes for the winning party
            if ($winningParty) {
                if (!isset($electoralVotesByParty[$winningParty])) {
                    $electoralVotesByParty[$winningParty] = 0;
                }
                $electoralVotesByParty[$winningParty] += $state['electrical_collage'];

            }
        }
        foreach ($electoralVotesByParty as $partyName => $electoralVotes) {
            if ($electoralVotes > 230) {
                // Retrieve president and vice-president candidates for the winning party
                $winningCandidates = Prediction::whereHas('votter_party', function ($query) use ($partyName) {
                    $query->where('party_name', $partyName);
                })->with('voter_candidate', 'votter_party')->get();

                break; // Assuming only one party can exceed 270 votes
            }

        }
        // $chose
        // return $winningCandidates;
        $chosenCandi = [];
        foreach ($winningCandidates as $chosen) {
            $chosenCandi[$chosen->position] = [
                'user_id' => $chosen->user_id,
                'voter_candidate_id' => $chosen->voter_candidate_id,
                'voter_party_id' => $chosen->votter_party_id,
                'position' => $chosen->position,
            ];
        }
        return $chosenCandi;

        // Calculate total electoral college votes for the winning parties
        // $totalElectoralCollege = array_sum($electoralVotesByParty);
        // dd($totalElectoralCollege);

        $response = [
            'message' => 'Success',
            // 'data' => $partyCountsByState,
            // 'total_electoral_college' => $totalElectoralCollege,
            'electoral_votes_by_party' => $electoralVotesByParty,
            // 'data_of_2020' => $data_of_2020,
            'winning_candidates' => $winningCandidates,
        ];

        return $this->sendSuccessResponse($response);
    }

    public function getVoterPartyCount()
    {
        $stateElections = UserPredictionParty::with('party', 'state')->get();
        // return $stateElections;
        // Initialize an empty array to hold the counts
        $partyCountsByState = [];
        $electoralVotesByParty = [];

        // Loop through each state election
        foreach ($stateElections as $stateElection) {
            // Check if the state relationship exists
            if ($stateElection->state) {
                $stateName = $stateElection->state->name;
                $partyName = $stateElection->party->party_name;
                $stateFlag = $stateElection->state->state_image_url;
                $stateMap = $stateElection->state->image_url;
                $electoralCollage = $stateElection->state->electrical_collage_number;
                $electoralCollage_number_1 = $stateElection->state->electrical_collage_number_1;

                // If the state id doesn't exist in the array, initialize it
                if (!isset($partyCountsByState[$stateName])) {
                    $partyCountsByState[$stateName] = [
                        'map_url' => $stateMap,
                        'state_image_url' => $stateFlag,
                        'electrical_collage' => $electoralCollage,
                        'electrical_collage_number_1' => $electoralCollage_number_1,
                        'winning_party' => null,
                    ];
                }

                // If the party name doesn't exist in the state's count, initialize it
                if (!isset($partyCountsByState[$stateName][$partyName])) {
                    $partyCountsByState[$stateName][$partyName] = 0;
                }

                // Increment the count for the party in the state
                $partyCountsByState[$stateName][$partyName]++;
            }
        }

        // Calculate percentages and find the winning party for each state
        foreach ($partyCountsByState as $stateName => &$state) {
            // Exclude 'state_image_url' and 'electrical_collage' from the total votes
            $totalVotes = array_sum(array_diff_key($state, ['state_image_url' => true, 'map_url' => true, 'electrical_collage' => true, 'electrical_collage_number_1' => true]));

            // Calculate percentage for each party
            foreach ($state as $key => &$count) {
                if ($key !== 'state_image_url' && $key !== 'map_url' && $key !== 'electrical_collage' && $key !== 'electrical_collage_number_1') {
                    $count = ($count / $totalVotes) * 100; // Calculate percentage
                }
            }

            // Find the winning party with the tie-breaking logic
            $winningParty = null;
            $maxPercentage = 0;
            $partiesWithMaxPercentage = [];

            foreach ($state as $key => $percentage) {
                if ($key !== 'state_image_url' && $key !== 'map_url' && $key !== 'electrical_collage' && $key !== 'electrical_collage_number_1') {
                    if ($percentage > $maxPercentage) {
                        $maxPercentage = $percentage;
                        $winningParty = $key;
                        $partiesWithMaxPercentage = [$key];
                    } elseif ($percentage == $maxPercentage) {
                        $partiesWithMaxPercentage[] = $key;
                    }
                }
            }

            // Apply tie-breaking logic
            if (count($partiesWithMaxPercentage) > 1) {
                if (in_array('Democratic', $partiesWithMaxPercentage) && in_array('Republican', $partiesWithMaxPercentage)) {
                    // If tie between Democratic and Republican, use the winning party of 2020
                    $data_of_2020_response = $this->ElectionState2020();
                    $data_of_2020 = $data_of_2020_response->getData(true);

                    $winningParty = $data_of_2020['data'][$stateName]['winning_party'];
                    // dd($winningParty);
                } elseif (in_array('Democratic', $partiesWithMaxPercentage) && in_array('Independent', $partiesWithMaxPercentage)) {
                    // If tie between Democratic and Independent, choose Democratic
                    $winningParty = 'Democratic';
                }
            }

            $state['winning_party'] = $winningParty;

            // Update the electoral votes for the winning party
            if ($winningParty) {
                if (!isset($electoralVotesByParty[$winningParty])) {
                    $electoralVotesByParty[$winningParty] = 0;
                }
                $electoralVotesByParty[$winningParty] += $state['electrical_collage_number_1'];
            }
        }

        $response = [
            'message' => 'Success',
            'data' => $partyCountsByState,
            'electoral_votes_by_party' => $electoralVotesByParty,
            'data_of_2020' => $this->ElectionState2020()->getData(true),
        ];
        return $this->sendSuccessResponse($response);
    }

    private function ElectionState2020()
    {
        // Fetch the state elections data for the year 2020
        $stateElections = PreviousElectionState::where('election_year', 2020)
            ->with('votter_party', 'user_state')
            ->whereNotNull('user_state_id')
            ->get();

        $partyCountsByState = [];

        // Loop through each state election
        foreach ($stateElections as $stateElection) {
            // Check if the state relationship exists
            if ($stateElection->user_state) {
                $stateName = $stateElection->user_state->name;
                $partyName = $stateElection->votter_party->party_name;
                $stateFlag = $stateElection->user_state->state_image_url;
                $electoralCollage = $stateElection->user_state->electrical_collage_number;
                $votePercentage = (float) $stateElection->vote_percentage;

                // If the state doesn't exist in the array, initialize it
                if (!isset($partyCountsByState[$stateName])) {
                    $partyCountsByState[$stateName] = [
                        'state_image_url' => $stateFlag,
                        'electrical_collage' => $electoralCollage,
                        'party_counts' => [],
                        'winning_party' => null,
                        'winning_percentage' => 0,
                    ];
                }

                // Store the vote percentage for the party in the state
                $partyCountsByState[$stateName]['party_counts'][$partyName] = $votePercentage;
            }
        }

        // Determine the winning party for each state
        foreach ($partyCountsByState as $stateName => &$stateData) {
            $winningParty = null;
            $maxVotes = 0;

            foreach ($stateData['party_counts'] as $partyName => $votePercentage) {
                if ($votePercentage > $maxVotes) {
                    $maxVotes = $votePercentage;
                    $winningParty = $partyName;
                }
            }

            $stateData['winning_party'] = $winningParty;
            $stateData['winning_percentage'] = $maxVotes;
        }

        // Prepare the final response
        $response = [
            'message' => 'Success',
            'data' => $partyCountsByState,
        ];

        return $this->sendSuccessResponse($response);
    }

    public function getPredictedPartyCandidate(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);
        if ($user_id) {
            $chosenpartycandidate = ChosenPartyCandidate::where('user_id', $user_id)
                ->with('voter_candidate', 'votter_party')
                ->get()
                ->sortBy(function ($item) {
                    return $item->votter_party->id; // Assuming votter_party has an 'id' field
                })
                ->values(); // Re-index the collection

            $response = [
                'message' => 'success',
                'chosen_candidate' => $chosenpartycandidate,
            ];
            return $this->sendSuccessResponse($response);
        } else {
            $response = [
                'message' => 'Failed please provide valid id',
            ];
            return $this->sendErrorResponse($response);
        }
    }

    //before electrical collage the state previous eletion state data.
    //the parties and the sate will display for election bt electrical collage.
    public function getStateParty()
    {
        try {

            $states = UserState::with(['previous_election_state' => function ($query) {
                $query->select('id', 'user_state_id', 'votter_party_id', 'election_year', 'vote_percentage');
            }])->get();
            // dd($s)

            $parties = VotterParty::all();

            // Transform party data into associative array for easier lookup
            $partyMap = $parties->pluck('party_name', 'id')->toArray();

            // Replace party IDs with party names
            $states->each(function ($state) use ($partyMap) {
                $state->previous_election_state->each(function ($electionState) use ($partyMap) {
                    $electionState->party_name = $partyMap[$electionState->votter_party_id] ?? null;
                    unset($electionState->votter_party_id); // Remove party ID
                });
            });

            $party = VotterParty::select('id', 'party_name')->get();

            $response = [
                'states' => $states,
                'parties' => $party,
            ];

            return $this->sendSuccessResponse($response);
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('Model not found', 404);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }

    // public function filter(Request $request)
    // {
    //     try {
    //         $filters = $request->all();
    //         if (!empty($filters)) {
    //             // Start building the query with eager loading of user predictions and related models
    //             $query = User::query()->with(
    //                 'user_predictions.voter_candidate',
    //                 'user_predictions.votter_party',
    //                 'user_gender',
    //                 'user_ethnicity',
    //                 'user_employement',
    //                 'user_country_birth',
    //                 'user_language',
    //                 'state',
    //                 'user_education',
    //                 'user_age',
    //                 'user_partyPredictions'
    //             );

    //             // Check if user_state_id is 71 (usa), if so include all states
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 // Do not filter by state, include all states
    //                 unset($filters['user_state_id']);
    //             }

    //             // Apply filters based on the provided criteria
    //             foreach ($filters as $key => $value) {
    //                 if ($key === 'user_state_id') {
    //                     // Modify the query to filter users by state
    //                     $query->where('user_state_id', $value);
    //                 } elseif (Str::endsWith($key, '_id')) {
    //                     // Remove the '_id' suffix to get the relationship name
    //                     $relationship = Str::beforeLast($key, '_id');
    //                     // Check if the relationship method exists
    //                     if (method_exists(User::class, $relationship)) {
    //                         // Directly apply the where clause on the relationship method
    //                         $query->whereHas($relationship, function ($q) use ($value) {
    //                             $q->where('id', $value);
    //                         });
    //                     } else {
    //                         return $this->sendErrorResponse('Invalid filter key: ' . $key, 400);
    //                     }
    //                 } elseif ($key === 'is_veteran') {
    //                     // Check if the user is a veteran, handle null or empty case
    //                     if (!is_null($value) && $value !== '') {
    //                         $query->where($key, $value === 'yes');
    //                     }
    //                 }
    //             }

    //             // Fetch filtered users
    //             $filteredUsers = $query->get();

    //             // Initialize counters for candidate and party votes
    //             $candidateVotes = [];
    //             $partyVotes = [];
    //             $uniqueUsers = [];

    //             // Track unique user votes
    //             foreach ($filteredUsers as $user) {
    //                 $userVotes = [
    //                     'president' => null,
    //                     'vice_president' => null,
    //                 ];

    //                 foreach ($user->user_predictions as $prediction) {
    //                     $position = $prediction->position;
    //                     $userVotes[$position] = $prediction;
    //                 }

    //                 if ($userVotes['president'] || $userVotes['vice_president']) {
    //                     $uniqueUsers[] = $userVotes;
    //                 }
    //             }

    //             $totalPredictions = count($uniqueUsers);

    //             foreach ($uniqueUsers as $userVote) {
    //                 $countedParties = [];

    //                 foreach ($userVote as $position => $prediction) {
    //                     if ($prediction) {
    //                         if ($prediction->voter_candidate_id !== null) {
    //                             $candidateId = $prediction->voter_candidate_id;
    //                             if (!isset($candidateVotes[$candidateId])) {
    //                                 $candidateVotes[$candidateId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->voter_candidate->candidate_name,
    //                                     'image' => $prediction->voter_candidate->candidate_image,
    //                                     'party_name' => $prediction->votter_party->party_name,
    //                                     'position' => $position,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }
    //                             $candidateVotes[$candidateId]['count']++;
    //                             // Count female and male votes
    //                             if (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Female') {
    //                                 $candidateVotes[$candidateId]['female_count']++;
    //                             } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                 $candidateVotes[$candidateId]['male_count']++;
    //                             }
    //                         }

    //                         if ($prediction->votter_party_id !== null) {
    //                             $partyId = $prediction->votter_party_id;
    //                             if (!isset($partyVotes[$partyId])) {
    //                                 $partyVotes[$partyId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->votter_party->party_name,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }

    //                             // Ensure the party is counted only once per user
    //                             if (!in_array($partyId, $countedParties)) {
    //                                 $partyVotes[$partyId]['count']++;
    //                                 $countedParties[] = $partyId;
    //                                 // Count female and male votes
    //                                 if (!empty($prediction->user->user_gender->name) && ($prediction->user->user_gender->name === 'Female')) {
    //                                     $partyVotes[$partyId]['female_count']++;
    //                                 } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                     $partyVotes[$partyId]['male_count']++;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             // Calculate percentages for each candidate and party
    //             $candidatePercentages = [];
    //             $partyPercentages = [];

    //             foreach ($candidateVotes as $candidateId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;
    //                 $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                 $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 $candidatePercentages[] = [
    //                     'candidate_id' => $candidateId,
    //                     'candidate_name' => $data['name'],
    //                     'candidate_image' => $data['image'],
    //                     'party_name' => $data['party_name'],
    //                     'position' => $data['position'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             foreach ($partyVotes as $partyId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;
    //                 $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                 $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 $partyPercentages[] = [
    //                     'party_id' => $partyId,
    //                     'party_name' => $data['name'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             // Build result for all users
    //             $allUsersResult = [
    //                 'totalPredictions' => $totalPredictions,
    //                 'candidate_percentages' => $candidatePercentages,
    //                 'party_percentages' => $partyPercentages,
    //             ];

    //             // Additional logic for user_state_id = 71 (usa)
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
    //                     return $user->user_predictions->count();
    //                 });
    //                 $allUsersResult['totalVoteCount'] = $totalVoteCount;
    //             }

    //             $response = [
    //                 'message' => 'success',
    //                 'data' => $allUsersResult,
    //             ];

    //             return $this->sendSuccessResponse($response);
    //         } else {
    //             return $this->sendErrorResponse('No filters provided', 400);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendErrorResponse($e->getMessage(), 500);
    //     } catch (ModelNotFoundException $e) {
    //         return $this->sendErrorResponse('User not found', 404); // Return appropriate error response
    //     }
    // }
    // public function filter(Request $request)
    // {
    //     try {
    //         $filters = $request->all();
    //         if (!empty($filters)) {
    //             // Start building the query with eager loading of user predictions and related models
    //             $query = User::query()
    //                 ->with(
    //                     'user_predictions.voter_candidate',
    //                     'user_predictions.votter_party',
    //                     'user_gender',
    //                     'user_ethnicity',
    //                     'user_employement',
    //                     'user_country_birth',
    //                     'user_language',
    //                     'user_state',
    //                     'user_education',
    //                     'user_age',
    //                     'user_partyPredictions'
    //                 );
    //                 // return $query;

    //             // Check if user_state_id is 71 (usa), if so include all states
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 // Do not filter by state, include all states
    //                 unset($filters['user_state_id']);
    //             }

    //             // Apply filters based on the provided criteria
    //             foreach ($filters as $key => $value) {
    //                 if ($key === 'user_state_id') {
    //                     // Modify the query to filter users by state
    //                     $query->where('user_state_id', $value);
    //                 } elseif (Str::endsWith($key, '_id')) {
    //                     // Remove the '_id' suffix to get the relationship name
    //                     $relationship = Str::beforeLast($key, '_id');
    //                     // Check if the relationship method exists
    //                     if (method_exists(User::class, $relationship)) {
    //                         // Directly apply the where clause on the relationship method
    //                         $query->whereHas($relationship, function ($q) use ($value) {
    //                             $q->where('id', $value);
    //                         });
    //                     } else {
    //                         return $this->sendErrorResponse('Invalid filter key: ' . $key, 400);
    //                     }
    //                 } elseif ($key === 'is_veteran') {
    //                     // Check if the user is a veteran, handle null or empty case
    //                     if (!is_null($value) && $value !== '') {
    //                         $query->where($key, $value === 'yes');
    //                     }
    //                 }
    //             }

    //             // Fetch filtered users
    //             $filteredUsers = $query->get();
    //             // dd($filteredUsers);

    //             // Initialize counters for candidate and party votes
    //             $candidateVotes = [];
    //             $partyVotes = [];
    //             $uniqueUsers = [];

    //             // Track unique user votes
    //             foreach ($filteredUsers as $user) {
    //                 $userVotes = [
    //                     'president' => null,
    //                     'vice_president' => null,
    //                 ];

    //                 foreach ($user->user_predictions as $prediction) {
    //                     $position = $prediction->position;
    //                     $userVotes[$position] = $prediction;
    //                 }

    //                 if ($userVotes['president'] || $userVotes['vice_president']) {
    //                     $uniqueUsers[] = $userVotes;
    //                 }
    //             }

    //             $totalPredictions = count($uniqueUsers);

    //             foreach ($uniqueUsers as $userVote) {
    //                 $countedParties = [];

    //                 foreach ($userVote as $position => $prediction) {
    //                     if ($prediction) {
    //                         if ($prediction->voter_candidate_id !== null) {
    //                             $candidateId = $prediction->voter_candidate_id;
    //                             if (!isset($candidateVotes[$candidateId])) {
    //                                 $candidateVotes[$candidateId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->voter_candidate->candidate_name,
    //                                     'image' => $prediction->voter_candidate->candidate_image,
    //                                     'party_name' => $prediction->votter_party->party_name,
    //                                     'position' => $position,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }
    //                             $candidateVotes[$candidateId]['count']++;
    //                             // Count female and male votes
    //                             if (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Female') {
    //                                 $candidateVotes[$candidateId]['female_count']++;
    //                             } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                 $candidateVotes[$candidateId]['male_count']++;
    //                             }
    //                         }

    //                         if ($prediction->votter_party_id !== null) {
    //                             $partyId = $prediction->votter_party_id;
    //                             if (!isset($partyVotes[$partyId])) {
    //                                 $partyVotes[$partyId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->votter_party->party_name,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }

    //                             // Ensure the party is counted only once per user
    //                             if (!in_array($partyId, $countedParties)) {
    //                                 $partyVotes[$partyId]['count']++;
    //                                 $countedParties[] = $partyId;
    //                                 // Count female and male votes
    //                                 if (!empty($prediction->user->user_gender->name) && ($prediction->user->user_gender->name === 'Female')) {
    //                                     $partyVotes[$partyId]['female_count']++;
    //                                 } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                     $partyVotes[$partyId]['male_count']++;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             // Calculate percentages for each candidate and party
    //             $candidatePercentages = [];
    //             $partyPercentages = [];

    //             foreach ($candidateVotes as $candidateId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;

    //                 // Ensure ratios sum to 100% if there are votes
    //                 if ($data['female_count'] > 0 && $data['male_count'] == 0) {
    //                     $femaleRatio = 100;
    //                     $maleRatio = 0;
    //                 } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
    //                     $maleRatio = 100;
    //                     $femaleRatio = 0;
    //                 } else {
    //                     $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                     $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 }

    //                 $candidatePercentages[] = [
    //                     'candidate_id' => $candidateId,
    //                     'candidate_name' => $data['name'],
    //                     'candidate_image' => $data['image'],
    //                     'party_name' => $data['party_name'],
    //                     'position' => $data['position'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             foreach ($partyVotes as $partyId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;

    //                 // Ensure ratios sum to 100% if there are votes
    //                 if ($data['female_count'] > 0 && $data['male_count'] == 0) {
    //                     $femaleRatio = 100;
    //                     $maleRatio = 0;
    //                 } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
    //                     $maleRatio = 100;
    //                     $femaleRatio = 0;
    //                 } else {
    //                     $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                     $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 }

    //                 $partyPercentages[] = [
    //                     'party_id' => $partyId,
    //                     'party_name' => $data['name'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             // Build result for all users
    //             $allUsersResult = [
    //                 'totalPredictions' => $totalPredictions,
    //                 'candidate_percentages' => $candidatePercentages,
    //                 'party_percentages' => $partyPercentages,
    //             ];

    //             // Additional logic for user_state_id = 71 (usa)
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
    //                     return $user->user_predictions->count();
    //                 });
    //                 $allUsersResult['totalVoteCount'] = $totalVoteCount;
    //             }

    //             $response = [
    //                 'message' => 'success',
    //                 'data' => $allUsersResult,
    //             ];

    //             return $this->sendSuccessResponse($response);
    //         } else {
    //             return $this->sendErrorResponse('No filters provided', 400);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendErrorResponse($e->getMessage(), 500);
    //     } catch (ModelNotFoundException $e) {
    //         return $this->sendErrorResponse('User not found', 404); // Return appropriate error response
    //     }
    // }

    // public function filter(Request $request)
    // {
    //     try {
    //         $filters = $request->all();
    //         if (!empty($filters)) {
    //             $query = User::query()
    //                 ->with(
    //                     'user_predictions.voter_candidate',
    //                     'user_predictions.votter_party',
    //                     'user_gender',
    //                     'user_ethnicity',
    //                     'user_employement',
    //                     'user_country_birth',
    //                     'user_language',
    //                     'user_state',
    //                     'user_education',
    //                     'user_age',
    //                     'user_partyPredictions'
    //                 );

    //             // dd($query);

    //             // Check if user_state_id is 71 (USA), if so include all states
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 // Do not filter by state, include all states
    //                 unset($filters['user_state_id']);
    //             }

    //             // Apply filters based on the provided criteria
    //             foreach ($filters as $key => $value) {
    //                 if ($key === 'user_state_id') {
    //                     // Modify the query to filter users by state
    //                     $query->where('user_state_id', $value);
    //                 } elseif (Str::endsWith($key, '_id')) {
    //                     // Remove the '_id' suffix to get the relationship name
    //                     $relationship = Str::beforeLast($key, '_id');
    //                     // dd($relationship);
    //                     // Check if the relationship method exists
    //                     if (method_exists(User::class, $relationship)) {
    //                         // Directly apply the where clause on the relationship method
    //                         $query->whereHas($relationship, function ($q) use ($value) {
    //                             $q->where('id', $value);
    //                         });
    //                     } else {
    //                         return $this->sendErrorResponse('Invalid filter key: ' . $key, 400);
    //                     }
    //                 } elseif ($key === 'is_veteran') {
    //                     // Check if the user is a veteran, handle null or empty case
    //                     if (!is_null($value) && $value !== '') {
    //                         $query->where($key, $value === 'yes');
    //                     }
    //                 }
    //             }
    //             // dd($query);

    //             // Debug: Enable query log
    //             \DB::enableQueryLog();

    //             // Fetch filtered users
    //             $filteredUsers = $query->get();
    //             // return $filteredUsers;

    //             // Debug: Log the SQL query
    //             $queries = \DB::getQueryLog();
    //             // dd($queries);
    //             Log::info($queries);

    //             // Debug: Log filtered users for verification
    //             \Log::info($filteredUsers);

    //             // Initialize counters for candidate and party votes
    //             $candidateVotes = [];
    //             $partyVotes = [];
    //             $uniqueUsers = [];

    //             // Track unique user votes
    //             foreach ($filteredUsers as $user) {
    //                 // return $user;
    //                 // dd($user);
    //                 $userVotes = [
    //                     'president' => null,
    //                     'vice_president' => null,
    //                 ];
    //                 // return $user;

    //                 foreach ($user->user_predictions as $prediction) {
    //                     // dd($prediction);
    //                     $position = $prediction->position;
    //                     $userVotes[$position] = $prediction;
    //                 }

    //                 if ($userVotes['president'] || $userVotes['vice_president']) {
    //                     $uniqueUsers[] = $userVotes;
    //                 }
    //             }
    //             // return $user;

    //             $totalPredictions = count($uniqueUsers);
    //             // dd($totalPredictions);

    //             foreach ($uniqueUsers as $userVote) {
    //                 $countedParties = [];

    //                 foreach ($userVote as $position => $prediction) {
    //                     if ($prediction) {
    //                         if ($prediction->voter_candidate_id !== null) {
    //                             $candidateId = $prediction->voter_candidate_id;
    //                             if (!isset($candidateVotes[$candidateId])) {
    //                                 $candidateVotes[$candidateId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->voter_candidate->candidate_name,
    //                                     'image' => $prediction->voter_candidate->candidate_image,
    //                                     'party_name' => $prediction->votter_party->party_name,
    //                                     'position' => $position,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }
    //                             $candidateVotes[$candidateId]['count']++;
    //                             // Count female and male votes
    //                             if (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Female') {
    //                                 $candidateVotes[$candidateId]['female_count']++;
    //                             } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                 $candidateVotes[$candidateId]['male_count']++;
    //                             }
    //                         }

    //                         if ($prediction->votter_party_id !== null) {
    //                             $partyId = $prediction->votter_party_id;
    //                             if (!isset($partyVotes[$partyId])) {
    //                                 $partyVotes[$partyId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->votter_party->party_name,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }

    //                             // Ensure the party is counted only once per user
    //                             if (!in_array($partyId, $countedParties)) {
    //                                 $partyVotes[$partyId]['count']++;
    //                                 $countedParties[] = $partyId;
    //                                 // Count female and male votes
    //                                 if (!empty($prediction->user->user_gender->name) && ($prediction->user->user_gender->name === 'Female')) {
    //                                     $partyVotes[$partyId]['female_count']++;
    //                                 } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                     $partyVotes[$partyId]['male_count']++;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             // Calculate percentages for each candidate and party
    //             $candidatePercentages = [];
    //             $partyPercentages = [];

    //             foreach ($candidateVotes as $candidateId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;

    //                 // Ensure ratios sum to 100% if there are votes
    //                 if ($data['female_count'] > 0 && $data['male_count'] == 0) {
    //                     $femaleRatio = 100;
    //                     $maleRatio = 0;
    //                 } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
    //                     $maleRatio = 100;
    //                     $femaleRatio = 0;
    //                 } else {
    //                     $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                     $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 }

    //                 $candidatePercentages[] = [
    //                     'candidate_id' => $candidateId,
    //                     'candidate_name' => $data['name'],
    //                     'candidate_image' => $data['image'],
    //                     'party_name' => $data['party_name'],
    //                     'position' => $data['position'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             foreach ($partyVotes as $partyId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;

    //                 // Ensure ratios sum to 100% if there are votes
    //                 if ($data['female_count'] > 0 && $data['male_count'] == 0) {
    //                     $femaleRatio = 100;
    //                     $maleRatio = 0;
    //                 } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
    //                     $maleRatio = 100;
    //                     $femaleRatio = 0;
    //                 } else {
    //                     $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                     $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 }

    //                 $partyPercentages[] = [
    //                     'party_id' => $partyId,
    //                     'party_name' => $data['name'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             // Build result for all users
    //             $allUsersResult = [
    //                 'totalPredictions' => $totalPredictions,
    //                 'candidate_percentages' => $candidatePercentages,
    //                 'party_percentages' => $partyPercentages,
    //             ];

    //             // Additional logic for user_state_id = 71 (USA)
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
    //                     return $user->user_predictions->count();
    //                 });
    //                 $allUsersResult['totalVoteCount'] = $totalVoteCount;
    //             }

    //             $response = [
    //                 'message' => 'success',
    //                 'data' => $allUsersResult,
    //             ];

    //             return $this->sendSuccessResponse($response);
    //         } else {
    //             return $this->sendErrorResponse('No filters provided', 400);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
    //     }
    // }

    // public function filter(Request $request)
    // {
    //     try {
    //         $filters = $request->all();
    //         if (!empty($filters)) {
    //             $query = User::query()
    //                 ->with(
    //                     'user_predictions.voter_candidate',
    //                     'user_predictions.votter_party',
    //                     'user_gender',
    //                     'user_ethnicity',
    //                     'user_employement',
    //                     'user_country_birth',
    //                     'user_language',
    //                     'user_state',
    //                     'user_education',
    //                     'user_age',
    //                     'user_partyPredictions'
    //                 );

    //             // Check if user_state_id is 71 (USA), if so include all states
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 unset($filters['user_state_id']);
    //             }

    //             // Apply filters based on the provided criteria
    //             foreach ($filters as $key => $value) {
    //                 if ($key === 'user_state_id') {
    //                     $query->where('user_state_id', $value);
    //                 } elseif (Str::endsWith($key, '_id')) {
    //                     $relationship = Str::beforeLast($key, '_id');
    //                     if (method_exists(User::class, $relationship)) {
    //                         $query->whereHas($relationship, function ($q) use ($value) {
    //                             $q->where('id', $value);
    //                         });
    //                     } else {
    //                         return $this->sendErrorResponse('Invalid filter key: ' . $key, 400);
    //                     }
    //                 } elseif ($key === 'is_veteran') {
    //                     if (!is_null($value) && $value !== '') {
    //                         $query->where($key, $value === 'yes');
    //                     }
    //                 }
    //             }

    //             // Debug: Enable query log
    //             \DB::enableQueryLog();

    //             // Fetch filtered users
    //             $filteredUsers = $query->get();

    //             // Debug: Log the SQL query
    //             $queries = \DB::getQueryLog();
    //             Log::info($queries);

    //             // Debug: Log filtered users for verification
    //             \Log::info($filteredUsers);

    //             // Initialize counters for candidate and party votes
    //             $candidateVotes = [];
    //             $partyVotes = [];
    //             $uniqueUsers = [];

    //             // Track unique user votes
    //             foreach ($filteredUsers as $user) {
    //                 $userVotes = [
    //                     'president' => null,
    //                     'vice_president' => null,
    //                 ];

    //                 foreach ($user->user_predictions as $prediction) {
    //                     $position = $prediction->position;
    //                     $userVotes[$position] = $prediction;
    //                 }

    //                 foreach ($user->user_partyPredictions as $partyPrediction) {
    //                     $position = $partyPrediction->position;
    //                     $userVotes[$position] = $partyPrediction;
    //                 }

    //                 if ($userVotes['president'] || $userVotes['vice_president']) {
    //                     $uniqueUsers[] = $userVotes;
    //                 }
    //             }

    //             $totalPredictions = count($uniqueUsers);

    //             foreach ($uniqueUsers as $userVote) {
    //                 $countedParties = [];

    //                 foreach ($userVote as $position => $prediction) {
    //                     if ($prediction) {
    //                         if ($prediction->voter_candidate_id !== null) {
    //                             $candidateId = $prediction->voter_candidate_id;
    //                             if (!isset($candidateVotes[$candidateId])) {
    //                                 $candidateVotes[$candidateId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->voter_candidate->candidate_name,
    //                                     'image' => $prediction->voter_candidate->candidate_image,
    //                                     'party_name' => $prediction->votter_party->party_name,
    //                                     'position' => $position,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }
    //                             $candidateVotes[$candidateId]['count']++;
    //                             // Count female and male votes
    //                             if (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Female') {
    //                                 $candidateVotes[$candidateId]['female_count']++;
    //                             } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                 $candidateVotes[$candidateId]['male_count']++;
    //                             }
    //                         }

    //                         if ($prediction->votter_party_id !== null) {
    //                             $partyId = $prediction->votter_party_id;
    //                             if (!isset($partyVotes[$partyId])) {
    //                                 $partyVotes[$partyId] = [
    //                                     'count' => 0,
    //                                     'name' => $prediction->votter_party->party_name,
    //                                     'female_count' => 0,
    //                                     'male_count' => 0,
    //                                 ];
    //                             }

    //                             // Ensure the party is counted only once per user
    //                             if (!in_array($partyId, $countedParties)) {
    //                                 $partyVotes[$partyId]['count']++;
    //                                 $countedParties[] = $partyId;
    //                                 // Count female and male votes
    //                                 if (!empty($prediction->user->user_gender->name) && ($prediction->user->user_gender->name === 'Female')) {
    //                                     $partyVotes[$partyId]['female_count']++;
    //                                 } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
    //                                     $partyVotes[$partyId]['male_count']++;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             // Calculate percentages for each candidate and party
    //             $candidatePercentages = [];
    //             $partyPercentages = [];

    //             foreach ($candidateVotes as $candidateId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;

    //                 // Ensure ratios sum to 100% if there are votes
    //                 if ($data['female_count'] > 0 && $data['male_count'] == 0) {
    //                     $femaleRatio = 100;
    //                     $maleRatio = 0;
    //                 } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
    //                     $maleRatio = 100;
    //                     $femaleRatio = 0;
    //                 } else {
    //                     $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                     $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 }

    //                 $candidatePercentages[] = [
    //                     'candidate_id' => $candidateId,
    //                     'candidate_name' => $data['name'],
    //                     'candidate_image' => $data['image'],
    //                     'party_name' => $data['party_name'],
    //                     'position' => $data['position'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             foreach ($partyVotes as $partyId => $data) {
    //                 $percentage = ($data['count'] / $totalPredictions) * 100;

    //                 // Ensure ratios sum to 100% if there are votes
    //                 if ($data['female_count'] > 0 && $data['male_count'] == 0) {
    //                     $femaleRatio = 100;
    //                     $maleRatio = 0;
    //                 } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
    //                     $maleRatio = 100;
    //                     $femaleRatio = 0;
    //                 } else {
    //                     $femaleRatio = ($data['female_count'] / $data['count']) * 100;
    //                     $maleRatio = ($data['male_count'] / $data['count']) * 100;
    //                 }

    //                 $partyPercentages[] = [
    //                     'party_id' => $partyId,
    //                     'party_name' => $data['name'],
    //                     'percentage' => $percentage,
    //                     'female_ratio' => $femaleRatio,
    //                     'male_ratio' => $maleRatio,
    //                 ];
    //             }

    //             // Build result for all users
    //             $allUsersResult = [
    //                 'totalPredictions' => $totalPredictions,
    //                 'candidate_percentages' => $candidatePercentages,
    //                 'party_percentages' => $partyPercentages,
    //             ];

    //             // Additional logic for user_state_id = 71 (USA)
    //             if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //                 $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
    //                     return $user->user_predictions->count();
    //                 });
    //                 $allUsersResult['totalVoteCount'] = $totalVoteCount;
    //             }

    //             $response = [
    //                 'message' => 'success',
    //                 'data' => $allUsersResult,
    //             ];

    //             return $this->sendSuccessResponse($response);
    //         } else {
    //             return $this->sendErrorResponse('No filters provided', 400);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
    //     }
    // }

    public function filter(Request $request)
    {
        try {
            $filters = $request->all();
            if (!empty($filters)) {
                $query = User::query()
                    ->with(
                        'user_predictions.voter_candidate',
                        'user_predictions.votter_party',
                        'user_gender',
                        'user_ethnicity',
                        'user_employement',
                        'user_country_birth',
                        'user_language',
                        'user_state',
                        'user_education',
                        'user_age',
                        'user_partyPredictions.votter_party.candidates',
                        'user_partyPredictions.state',
                    );

                // Check if user_state_id is 71 (USA), if so include all states
                if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
                    unset($filters['user_state_id']);
                }

                // Apply filters based on the provided criteria
                foreach ($filters as $key => $value) {
                    if ($key === 'user_state_id') {
                        $query->where('user_state_id', $value);
                    } elseif (Str::endsWith($key, '_id')) {
                        $relationship = Str::beforeLast($key, '_id');
                        if (method_exists(User::class, $relationship)) {
                            $query->whereHas($relationship, function ($q) use ($value) {
                                $q->where('id', $value);
                            });
                        } else {
                            return $this->sendErrorResponse('Invalid filter key: ' . $key, 400);
                        }
                    } elseif ($key === 'is_veteran') {
                        if (!is_null($value) && $value !== '') {
                            $query->where($key, $value === 'yes');
                        }
                    }
                }

                // Debug: Enable query log
                \DB::enableQueryLog();

                // Fetch filtered users
                $filteredUsers = $query->get();

                // Debug: Log the SQL query
                $queries = \DB::getQueryLog();
                Log::info($queries);

                // Debug: Log filtered users for verification
                \Log::info($filteredUsers);

                // Initialize counters for candidate and party votes
                $candidateVotes = [];
                $partyVotes = [];
                $uniqueUsers = [];

                // Track unique user votes
                foreach ($filteredUsers as $user) {
                    $userVotes = [
                        'president' => null,
                        'vice_president' => null,
                    ];

                    foreach ($user->user_predictions as $prediction) {
                        $position = $prediction->position;
                        $userVotes[$position] = $prediction;
                    }

                    // dd($user->user_partyPredictions);
                    // return $user->user_partyPredictions;
                    foreach ($user->user_partyPredictions as $partyPrediction) {
                        // dd($partyPrediction);
                        // return $partyPrediction;
                        $position = $partyPrediction->position;
                        $userVotes[$position] = $partyPrediction;
                        // dd($userVotes[$position]);
                    }

                    if ($userVotes['president'] || $userVotes['vice_president']) {
                        $uniqueUsers[] = $userVotes;
                        // dd($uniqueUsers);
                    }
                }

                // Count total predictions from both types
                $totalPredictions = 0;
                foreach ($filteredUsers as $user) {
                    // dd($user);
                    if ($user->user_predictions->isNotEmpty() || $user->user_partyPredictions->isNotEmpty()) {
                        $totalPredictions++;
                    }
                }

                foreach ($uniqueUsers as $userVote) {
                    $countedParties = [];

                    foreach ($userVote as $position => $prediction) {
                        if ($prediction) {
                            if ($prediction->voter_candidate_id !== null) {
                                $candidateId = $prediction->voter_candidate_id;
                                if (!isset($candidateVotes[$candidateId])) {
                                    $candidateVotes[$candidateId] = [
                                        'count' => 0,
                                        'name' => $prediction->voter_candidate->candidate_name,
                                        'image' => $prediction->voter_candidate->candidate_image,
                                        'party_name' => $prediction->votter_party->party_name,
                                        'position' => $position,
                                        'female_count' => 0,
                                        'male_count' => 0,
                                    ];
                                }
                                $candidateVotes[$candidateId]['count']++;
                                // Count female and male votes
                                if (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Female') {
                                    $candidateVotes[$candidateId]['female_count']++;
                                } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
                                    $candidateVotes[$candidateId]['male_count']++;
                                }
                            }

                            if ($prediction->votter_party_id !== null) {
                                $partyId = $prediction->votter_party_id;
                                if (!isset($partyVotes[$partyId])) {
                                    $partyVotes[$partyId] = [
                                        'count' => 0,
                                        'name' => $prediction->votter_party->party_name,
                                        'female_count' => 0,
                                        'male_count' => 0,
                                    ];
                                }

                                // Ensure the party is counted only once per user
                                if (!in_array($partyId, $countedParties)) {
                                    $partyVotes[$partyId]['count']++;
                                    $countedParties[] = $partyId;
                                    // Count female and male votes
                                    if (!empty($prediction->user->user_gender->name) && ($prediction->user->user_gender->name === 'Female')) {
                                        $partyVotes[$partyId]['female_count']++;
                                    } elseif (!empty($prediction->user->user_gender->name) && $prediction->user->user_gender->name === 'Male') {
                                        $partyVotes[$partyId]['male_count']++;
                                    }
                                }
                            }
                        }
                    }
                }

                // Calculate percentages for each candidate and party
                $candidatePercentages = [];
                $partyPercentages = [];

                foreach ($candidateVotes as $candidateId => $data) {
                    $percentage = ($data['count'] / $totalPredictions) * 100;

                    // Ensure ratios sum to 100% if there are votes
                    if ($data['female_count'] > 0 && $data['male_count'] == 0) {
                        $femaleRatio = 100;
                        $maleRatio = 0;
                    } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
                        $maleRatio = 100;
                        $femaleRatio = 0;
                    } else {
                        $femaleRatio = ($data['female_count'] / $data['count']) * 100;
                        $maleRatio = ($data['male_count'] / $data['count']) * 100;
                    }

                    $candidatePercentages[] = [
                        'candidate_id' => $candidateId,
                        'candidate_name' => $data['name'],
                        'candidate_image' => $data['image'],
                        'party_name' => $data['party_name'],
                        'position' => $data['position'],
                        'percentage' => $percentage,
                        'female_ratio' => $femaleRatio,
                        'male_ratio' => $maleRatio,
                    ];
                }

                foreach ($partyVotes as $partyId => $data) {
                    $percentage = ($data['count'] / $totalPredictions) * 100;

                    // Ensure ratios sum to 100% if there are votes
                    if ($data['female_count'] > 0 && $data['male_count'] == 0) {
                        $femaleRatio = 100;
                        $maleRatio = 0;
                    } elseif ($data['male_count'] > 0 && $data['female_count'] == 0) {
                        $maleRatio = 100;
                        $femaleRatio = 0;
                    } else {
                        $femaleRatio = ($data['female_count'] / $data['count']) * 100;
                        $maleRatio = ($data['male_count'] / $data['count']) * 100;
                    }

                    $partyPercentages[] = [
                        'party_id' => $partyId,
                        'party_name' => $data['name'],
                        'percentage' => $percentage,
                        'female_ratio' => $femaleRatio,
                        'male_ratio' => $maleRatio,
                    ];
                }

                // Build result for all users
                $allUsersResult = [
                    'totalPredictions' => $totalPredictions,
                    'candidate_percentages' => $candidatePercentages,
                    'party_percentages' => $partyPercentages,
                ];

                // Additional logic for user_state_id = 71 (USA)
                if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
                    $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
                        return $user->user_predictions->count();
                    });
                    $allUsersResult['totalVoteCount'] = $totalVoteCount;
                }

                $response = [
                    'message' => 'success',
                    'data' => $allUsersResult,
                ];

                return $this->sendSuccessResponse($response);
            } else {
                return $this->sendErrorResponse('No filters provided', 400);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    // public function filter(Request $request)
    // {
    //     // try {
    //         $filters = $request->all();
    //         if (empty($filters)) {
    //             return $this->sendErrorResponse('No filters provided', 400);
    //         }

    //         $userQuery = $this->buildUserQuery($filters);
    //         $partyQuery = $this->buildPartyQuery($filters);

    //         \DB::enableQueryLog();

    //         $filteredUsers = $userQuery->get();
    //         $filteredParties = $partyQuery->get();

    //         $queries = \DB::getQueryLog();
    //         Log::info($queries);

    //         \Log::info($filteredUsers);
    //         \Log::info($filteredParties);

    //         list($candidateVotes, $partyVotes, $totalPredictions) = $this->calculateVotes($filteredUsers, $filteredParties);

    //         $allUsersResult = [
    //             'totalPredictions' => $totalPredictions,
    //             'candidate_percentages' => $this->calculatePercentages($candidateVotes, $totalPredictions),
    //             'party_percentages' => $this->calculatePercentages($partyVotes, $totalPredictions),
    //         ];

    //         if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //             $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
    //                 return $user->user_predictions->count();
    //             });
    //             $allUsersResult['totalVoteCount'] = $totalVoteCount;
    //         }

    //         return $this->sendSuccessResponse(['message' => 'success', 'data' => $allUsersResult]);
    //     // } catch (\Exception $e) {
    //     //     return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
    //     // }
    // }

    // private function buildUserQuery($filters)
    // {
    //     $query = User::query()->with(
    //         'user_predictions.voter_candidate',
    //         'user_predictions.votter_party',
    //         'user_gender',
    //         'user_ethnicity',
    //         'user_employement',
    //         'user_country_birth',
    //         'user_language',
    //         'user_state',
    //         'user_education',
    //         'user_age',
    //         'user_partyPredictions'
    //     );

    //     return $this->applyFilters($query, $filters);
    // }

    // private function buildPartyQuery($filters)
    // {
    //     $query = User::query()->with(
    //         'user_partyPredictions.votter_party',
    //         'user_gender',
    //         'user_ethnicity',
    //         'user_employement',
    //         'user_country_birth',
    //         'user_language',
    //         'user_state',
    //         'user_education',
    //         'user_age'
    //     );

    //     return $this->applyFilters($query, $filters);
    // }

    // private function applyFilters($query, $filters)
    // {
    //     foreach ($filters as $key => $value) {
    //         if ($key === 'user_state_id') {
    //             $query->where('user_state_id', $value);
    //         } elseif (Str::endsWith($key, '_id')) {
    //             $relationship = Str::beforeLast($key, '_id');
    //             if (method_exists(User::class, $relationship)) {
    //                 $query->whereHas($relationship, function ($q) use ($value) {
    //                     $q->where('id', $value);
    //                 });
    //             } else {
    //                 throw new \BadMethodCallException('Invalid filter key: ' . $key);
    //             }
    //         } elseif ($key === 'is_veteran') {
    //             if (!is_null($value) && $value !== '') {
    //                 $query->where($key, $value === 'yes');
    //             }
    //         }
    //     }
    //     return $query;
    // }

    // private function calculateVotes($filteredUsers, $filteredParties)
    // {
    //     $candidateVotes = [];
    //     $partyVotes = [];
    //     $uniqueUsers = [];

    //     foreach ($filteredUsers as $user) {
    //         $userVotes = ['president' => null, 'vice_president' => null];

    //         foreach ($user->user_predictions as $prediction) {
    //             $position = $prediction->position;
    //             $userVotes[$position] = $prediction;
    //         }

    //         if ($userVotes['president'] || $userVotes['vice_president']) {
    //             $uniqueUsers[] = $userVotes;
    //         }
    //     }

    //     foreach ($filteredParties as $user) {
    //         foreach ($user->user_partyPredictions as $prediction) {
    //             if ($prediction->votter_party_id !== null) {
    //                 $partyId = $prediction->votter_party_id;
    //                 if (!isset($partyVotes[$partyId])) {
    //                     $partyVotes[$partyId] = [
    //                         'count' => 0,
    //                         'name' => $prediction->votter_party->party_name,
    //                         'female_count' => 0,
    //                         'male_count' => 0,
    //                     ];
    //                 }

    //                 $partyVotes[$partyId]['count']++;
    //                 if (!empty($user->user_gender->name)) {
    //                     if ($user->user_gender->name === 'Female') {
    //                         $partyVotes[$partyId]['female_count']++;
    //                     } elseif ($user->user_gender->name === 'Male') {
    //                         $partyVotes[$partyId]['male_count']++;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     $totalPredictions = count($uniqueUsers) + $filteredParties->count();

    //     foreach ($uniqueUsers as $userVote) {
    //         foreach ($userVote as $position => $prediction) {
    //             if ($prediction) {
    //                 $this->countCandidateVotes($candidateVotes, $prediction, $position);
    //             }
    //         }
    //     }

    //     return [$candidateVotes, $partyVotes, $totalPredictions];
    // }

    // private function countCandidateVotes(&$candidateVotes, $prediction, $position)
    // {
    //     $candidateId = $prediction->voter_candidate_id;
    //     if (!isset($candidateVotes[$candidateId])) {
    //         $candidateVotes[$candidateId] = [
    //             'count' => 0,
    //             'name' => $prediction->voter_candidate->candidate_name,
    //             'image' => $prediction->voter_candidate->candidate_image,
    //             'party_name' => $prediction->votter_party->party_name,
    //             'position' => $position,
    //             'female_count' => 0,
    //             'male_count' => 0,
    //         ];
    //     }
    //     $candidateVotes[$candidateId]['count']++;
    //     if (!empty($prediction->user->user_gender->name)) {
    //         if ($prediction->user->user_gender->name === 'Female') {
    //             $candidateVotes[$candidateId]['female_count']++;
    //         } elseif ($prediction->user->user_gender->name === 'Male') {
    //             $candidateVotes[$candidateId]['male_count']++;
    //         }
    //     }
    // }

    // private function calculatePercentages($votes, $totalPredictions)
    // {
    //     $percentages = [];
    //     foreach ($votes as $id => $data) {
    //         $percentage = ($data['count'] / $totalPredictions) * 100;
    //         $femaleRatio = $data['female_count'] > 0 ? ($data['female_count'] / $data['count']) * 100 : 0;
    //         $maleRatio = $data['male_count'] > 0 ? ($data['male_count'] / $data['count']) * 100 : 0;
    //         $percentages[] = [
    //             'id' => $id,
    //             // 'name' => $data['name'],
    //             'image' => $data['image'] ?? null,
    //             'party_name' => $data['name'],
    //             // 'position' => $data['position'],
    //             'percentage' => $percentage,
    //             'female_ratio' => $femaleRatio,
    //             'male_ratio' => $maleRatio,
    //         ];
    //     }
    //     return $percentages;
    // }

    // update
    // public function filter(Request $request)
    // {
    //     // try {
    //     $filters = $request->all();

    //     // Check if no filters provided, set default state filter for USA (assuming state id for USA is 1)
    //     if (empty($filters)) {
    //         $filters['user_state_id'] = 71;
    //     }

    //     $userQuery = $this->buildUserQuery($filters);
    //     $partyQuery = $this->buildPartyQuery($filters);

    //     \DB::enableQueryLog();

    //     $filteredUsers = $userQuery->get();
    //     $filteredParties = $partyQuery->get();

    //     $queries = \DB::getQueryLog();
    //     Log::info($queries);

    //     \Log::info($filteredUsers);
    //     \Log::info($filteredParties);

    //     list($candidateVotes, $partyVotes, $totalPredictions) = $this->calculateVotes($filteredUsers, $filteredParties);
    //     // $test= $this->calculatePercentages($partyVotes, $totalPredictions);
    //     // return $test;
    //     $allUsersResult = [
    //         'totalPredictions' => $totalPredictions,
    //         'candidate_percentages' => $this->calculatePercentages($candidateVotes, $totalPredictions),
    //         'party_percentages' => $this->calculatePercentages($partyVotes, $totalPredictions),
    //     ];

    //     // if (isset($filters['user_state_id']) && $filters['user_state_id'] == 71) {
    //     //     $totalVoteCount = User::where('user_state_id', '<>', 71)->sum(function ($user) {
    //     //         return $user->user_predictions->count();
    //     //     });
    //     //     $allUsersResult['totalVoteCount'] = $totalVoteCount;
    //     // }

    //     return $this->sendSuccessResponse(['message' => 'success', 'data' => $allUsersResult]);
    //     // } catch (\Exception $e) {
    //     //     return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
    //     // }
    // }

    // private function buildUserQuery($filters)
    // {
    //     $query = User::query()->with(
    //         'user_predictions.voter_candidate',
    //         'user_predictions.votter_party',
    //         'user_gender',
    //         'user_ethnicity',
    //         'user_employement',
    //         'user_country_birth',
    //         'user_language',
    //         'user_state',
    //         'user_education',
    //         'user_age',
    //         'user_partyPredictions'
    //     );

    //     return $this->applyFilters($query, $filters);
    // }

    // private function buildPartyQuery($filters)
    // {
    //     $query = User::query()->with(
    //         'user_partyPredictions.votter_party',
    //         'user_gender',
    //         'user_ethnicity',
    //         'user_employement',
    //         'user_country_birth',
    //         'user_language',
    //         'user_state',
    //         'user_education',
    //         'user_age'
    //     );

    //     return $this->applyFilters($query, $filters);
    // }

    // private function applyFilters($query, $filters)
    // {
    //     if (!isset($filters['user_state_id'])) {
    //         // Set default state ID for USA
    //         $filters['user_state_id'] = 1;
    //     }

    //     foreach ($filters as $key => $value) {
    //         if ($key === 'user_state_id') {
    //             $query->where('user_state_id', $value);
    //         } elseif (Str::endsWith($key, '_id')) {
    //             $relationship = Str::beforeLast($key, '_id');
    //             if (method_exists(User::class, $relationship)) {
    //                 $query->whereHas($relationship, function ($q) use ($value) {
    //                     $q->where('id', $value);
    //                 });
    //             } else {
    //                 throw new \BadMethodCallException('Invalid filter key: ' . $key);
    //             }
    //         } elseif ($key === 'is_veteran') {
    //             if (!is_null($value) && $value !== '') {
    //                 $query->where($key, $value === 'yes');
    //             }
    //         }
    //     }
    //     return $query;
    // }

    // private function calculateVotes($filteredUsers, $filteredParties)
    // {
    //     $candidateVotes = [];
    //     $partyVotes = [];
    //     $uniqueUsers = [];

    //     foreach ($filteredUsers as $user) {
    //         $userVotes = ['president' => null, 'vice_president' => null];

    //         foreach ($user->user_predictions as $prediction) {
    //             $position = $prediction->position;
    //             $userVotes[$position] = $prediction;
    //         }

    //         if ($userVotes['president'] || $userVotes['vice_president']) {
    //             $uniqueUsers[] = $userVotes;
    //         }
    //     }

    //     foreach ($filteredParties as $user) {
    //         foreach ($user->user_partyPredictions as $prediction) {
    //             // return $prediction;
    //             if ($prediction->votter_party_id !== null) {
    //                 $partyId = $prediction->votter_party_id;
    //                 if (!isset($partyVotes[$partyId])) {
    //                     $partyVotes[$partyId] = [
    //                         'count' => 0,
    //                         'name' => $prediction->votter_party->party_name,
    //                         'candidate' => VoterCandidate::find($prediction->votter_party_id),
    //                         'female_count' => 0,
    //                         'male_count' => 0,
    //                     ];
    //                 }


    //                 $partyVotes[$partyId]['count']++;
    //                 if (!empty($user->user_gender->name)) {
    //                     if ($user->user_gender->name === 'Female') {
    //                         $partyVotes[$partyId]['female_count']++;
    //                     } elseif ($user->user_gender->name === 'Male') {
    //                         $partyVotes[$partyId]['male_count']++;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     $totalPredictions = count($uniqueUsers) + $filteredParties->count();

    //     foreach ($uniqueUsers as $userVote) {
    //         foreach ($userVote as $position => $prediction) {
    //             if ($prediction) {
    //                 $this->countCandidateVotes($candidateVotes, $prediction, $position);
    //             }
    //         }
    //     }

    //     return [$candidateVotes, $partyVotes, $totalPredictions];
    // }

    // private function countCandidateVotes(&$candidateVotes, $prediction, $position)
    // {
    //     $candidateId = $prediction->voter_candidate_id;
    //     if (!isset($candidateVotes[$candidateId])) {
    //         $candidateVotes[$candidateId] = [
    //             'count' => 0,
    //             'name' => $prediction->voter_candidate->candidate_name,
    //             'image' => $prediction->voter_candidate->candidate_image,
    //             'party_name' => $prediction->votter_party->party_name,
    //             'position' => $position,
    //             'female_count' => 0,
    //             'male_count' => 0,
    //         ];
    //     }
    //     $candidateVotes[$candidateId]['count']++;
    //     if (!empty($prediction->user->user_gender->name)) {
    //         if ($prediction->user->user_gender->name === 'Female') {
    //             $candidateVotes[$candidateId]['female_count']++;
    //         } elseif ($prediction->user->user_gender->name === 'Male') {
    //             $candidateVotes[$candidateId]['male_count']++;
    //         }
    //     }
    // }

    // private function calculatePercentages($votes, $totalPredictions)
    // {
        
    //     $percentages = [];
    //     foreach ($votes as $id => $data) {
    //         $percentage = ($data['count'] / $totalPredictions) * 100;
    //         $femaleRatio = $data['female_count'] > 0 ? ($data['female_count'] / $data['count']) * 100 : 0;
    //         $maleRatio = $data['male_count'] > 0 ? ($data['male_count'] / $data['count']) * 100 : 0;
    //         $percentages[] = [
    //             'id' => $id,
    //             // 'name' => $data['name'],
    //             'image' => $data['image'] ?? null,
    //             'party_name' => $data['name'],
    //             'candidate' => $data['candidate']['name'],
    //             // 'position' => $data['position'],
    //             'percentage' => $percentage,
    //             'female_ratio' => $femaleRatio,
    //             'male_ratio' => $maleRatio,
    //         ];
    //     }
    //     return $percentages;
    // }
    //end

    public function ratioVoterCandidateRole()
    {
        try {

            $voteCounts = Prediction::select('voter_candidate_id', 'position')
                ->selectRaw('count(*) as vote_count')
                ->groupBy('voter_candidate_id', 'position')
                ->with('voter_candidate')
                ->get();

            //Calculate total votes for each position
            $totalPresidentVotes = $voteCounts->where('position', 'president')->sum('vote_count');
            $totalVicePresidentVotes = $voteCounts->where('position', 'vice_president')->sum('vote_count');

            // dd($totalPresidentVotes,$totalVicePresidentVotes);

            // Separate votes by position
            $presidentVotes = $voteCounts->where('position', 'president');
            $vicePresidentVotes = $voteCounts->where('position', 'vice_president');

            //Sort candidates by vote count in descending order
            $sortedPresidentVotes = $presidentVotes->sortByDesc('vote_count');
            $sortedVicePresidentVotes = $vicePresidentVotes->sortByDesc('vote_count');

            //Get top 3 candidates for each position
            $top3PresidentVotes = $sortedPresidentVotes->take(3);
            $top3VicePresidentVotes = $sortedVicePresidentVotes->take(3);

            $topPresidentResults = $top3PresidentVotes->map(function ($vote) use ($totalPresidentVotes) {
                return [
                    'candidate_name' => $vote->voter_candidate->candidate_name,
                    'vote_count' => $vote->vote_count,
                    'vote_percentage' => $totalPresidentVotes > 0 ? round(($vote->vote_count / $totalPresidentVotes) * 100) : 0,
                ];
            })->values();

            $topVicePresidentResults = $top3VicePresidentVotes->map(function ($vote) use ($totalVicePresidentVotes) {
                return [
                    'candidate_name' => $vote->voter_candidate->candidate_name,
                    'vote_count' => $vote->vote_count,
                    'vote_percentage' => $totalVicePresidentVotes > 0 ? round(($vote->vote_count / $totalVicePresidentVotes) * 100) : 0,
                ];
            })->values();

            // Combine the results
            $response = [
                'message' => 'Sucess',
                'president' => $topPresidentResults,
                'vice_president' => $topVicePresidentResults,
            ];

            return $this->sendSuccessResponse($response);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 500);
        }
    }

    public function userVotings(Request $request)
    {
        // dd(12);
        try {
            $user = Auth::user();

            // dd($user);
            $users = Prediction::with('voter_candidate', 'votter_party')->where('user_id', $user->id)->get();
            // return $users;
            $userChosenCandidates = ChosenPartyCandidate::with('voter_candidate', 'votter_party')->where('user_id', $user->id)->get();
            //  return $userChosenCandidates;
            $voterCandidateDetails = $users->map(function ($predict) use ($users) {
                return [
                    'candidate_name' => $predict->voter_candidate->candidate_name,
                    'candidate_image' => $predict->voter_candidate->candidate_image,
                    'party_name' => $predict->votter_party->party_name,
                    'party_badge' => $predict->votter_party->party_badge,
                    'position' => $predict->position,
                ];
            });
            // dd($voterCandidateDetails);

            $candidateChosens = $userChosenCandidates->map(function ($candidate) use ($userChosenCandidates) {
                return [
                    'candidate_name' => $candidate->voter_candidate->candidate_name,
                    'candidate_image' => $candidate->voter_candidate->candidate_image,
                    'party_name' => $candidate->votter_party->party_name,
                    'party_badge' => $candidate->votter_party->party_badge,
                    'position' => $candidate->position,
                ];
            });

            $response = [
                'message' => 'success',
                'SelectedCandidates' => $candidateChosens,
                'PredictedCandidateDetails' => $voterCandidateDetails,

            ];
            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 500);
        }

    }

    //this api is for  automaticaly predict presendent and vice_president
    //from electroral collage its electroral collage will excede from 270
    public function getFinalizeCandidateElectroral()
    {
        $stateElections = UserPredictionParty::where('user_id', auth()->id())->with('party', 'state')->get();
        $partyCountsByState = [];
        $electoralVotesByParty = [];

        // Collect vote counts by state and party
        foreach ($stateElections as $stateElection) {
            if ($stateElection->state) {
                $stateName = $stateElection->state->name;
                $partyName = $stateElection->party->party_name;
                $stateFlag = $stateElection->state->state_image_url;
                $stateMap = $stateElection->state->image_url;
                $electoralCollage = $stateElection->state->electrical_collage_number;
                $electoralCollage_1 = $stateElection->state->electrical_collage_number_1;

                // Initialize state entry if it doesn't exist
                if (!isset($partyCountsByState[$stateName])) {
                    $partyCountsByState[$stateName] = [
                        'map_url' => $stateMap,
                        'state_image_url' => $stateFlag,
                        'electrical_collage' => $electoralCollage,
                        'electrical_collage_1' => $electoralCollage_1,
                        'parties' => [],
                    ];
                }

                // Initialize party count if it doesn't exist
                if (!isset($partyCountsByState[$stateName]['parties'][$partyName])) {
                    $partyCountsByState[$stateName]['parties'][$partyName] = 0;
                }

                // Increment the party count for the state
                $partyCountsByState[$stateName]['parties'][$partyName]++;
            }
        }

        // Calculate percentages and find the winning party
        foreach ($partyCountsByState as $stateName => &$stateData) {
            $totalVotes = array_sum($stateData['parties']);
            $maxPercentage = 0;
            $winningParty = null;

            foreach ($stateData['parties'] as $partyName => &$votes) {
                $votes = ($votes / $totalVotes) * 100;

                if ($votes > $maxPercentage) {
                    $maxPercentage = $votes;
                    $winningParty = $partyName;
                }
            }

            $stateData['winning_party'] = $winningParty;

            // Update the electoral votes for the winning party
            if ($winningParty) {
                if (!isset($electoralVotesByParty[$winningParty])) {
                    $electoralVotesByParty[$winningParty] = 0;
                }
                $electoralVotesByParty[$winningParty] += $stateData['electrical_collage_1'];
            }
        }

        $winningCandidates = [];
        $candidates = [];
        $presidentCandidate = null;
        $vicePresidentCandidate = null;

        $highestElectoralVotes = 0;
        $chosenParty = null;

// Find the party with the highest electoral votes among the specified parties
        foreach ($electoralVotesByParty as $partyName => $electoralVotes) {
            if ($partyName === 'Democratic' || $partyName === 'Republican' || $partyName === "Independent('Kennedy')") {
                if ($electoralVotes > $highestElectoralVotes) {
                    $highestElectoralVotes = $electoralVotes;
                    $chosenParty = $partyName;
                }
            }
        }
        // dd($highestElectoralVotes,$chosenParty);
        if ($chosenParty || $highestElectoralVotes > 270) {
            $candidates = ChosenPartyCandidate::whereHas('votter_party', function ($query) use ($chosenParty) {
                $query->where('party_name', $chosenParty);
            })
                ->where('user_id', auth()->id())
                ->whereIn('position', ['president', 'vice_president'])
                ->with('votter_party', 'voter_candidate')
                ->get();
            $groupedCandidates = $candidates->groupBy('position')->map(function ($item) {
                return $item->first();
            });
            $presidentCandidate = $groupedCandidates->get('president', null);
            $vicePresidentCandidate = $groupedCandidates->get('vice_president', null);
            // $this->test($presidentCandidate, $vicePresidentCandidate);
        }
        // return $candidates;
        $prediction = Prediction::query()->where('user_id', auth()->id())->get();
        if ($prediction->isNotEmpty()) {
            foreach ($prediction as $pred) {
                // Update for president
                if ($pred->position === 'president' && $presidentCandidate) {
                    $pred->voter_candidate_id = $presidentCandidate->voter_candidate_id;
                    $pred->votter_party_id = $presidentCandidate->votter_party_id;
                    $pred->position = $presidentCandidate->position;
                    $pred->save();
                }

                // Update for vice president
                if ($pred->position === 'vice_president' && $vicePresidentCandidate) {
                    $pred->voter_candidate_id = $vicePresidentCandidate->voter_candidate_id;
                    $pred->votter_party_id = $vicePresidentCandidate->votter_party_id;
                    $pred->position = $vicePresidentCandidate->position;
                    $pred->save();
                }
            }
        } else {
            // Create new entry for president
            if ($presidentCandidate) {
                Prediction::create([
                    'user_id' => auth()->id(),
                    'voter_candidate_id' => $presidentCandidate->voter_candidate_id,
                    'votter_party_id' => $presidentCandidate->votter_party_id,
                    'position' => 'president',
                ]);
            }

            // Create new entry for vice president
            if ($vicePresidentCandidate) {
                Prediction::create([
                    'user_id' => auth()->id(),
                    'voter_candidate_id' => $vicePresidentCandidate->voter_candidate_id,
                    'votter_party_id' => $vicePresidentCandidate->votter_party_id,
                    'position' => 'vice_president',
                ]);
            }
        }

        $predictionState = ChosenPresidentVicePresidentStates::query()->where('user_id', auth()->id())->get();
        if ($predictionState->isNotEmpty()) {
            foreach ($predictionState as $predState) {
                // Update for president
                if ($predState->position === 'president' && $presidentCandidate) {
                    $predState->voter_candidate_id = $presidentCandidate->voter_candidate_id;
                    $predState->votter_party_id = $presidentCandidate->votter_party_id;
                    $predState->position = $presidentCandidate->position;
                    $predState->save();
                }

                // Update for vice president
                if ($predState->position === 'vice_president' && $vicePresidentCandidate) {
                    $predState->voter_candidate_id = $vicePresidentCandidate->voter_candidate_id;
                    $predState->votter_party_id = $vicePresidentCandidate->votter_party_id;
                    $predState->position = $vicePresidentCandidate->position;
                    $predState->save();
                }
            }
        } else {
            // Create new entry for president
            if ($presidentCandidate) {
                ChosenPresidentVicePresidentStates::create([
                    'user_id' => auth()->id(),
                    'voter_candidate_id' => $presidentCandidate->voter_candidate_id,
                    'votter_party_id' => $presidentCandidate->votter_party_id,
                    'position' => 'president',
                ]);
            }

            // Create new entry for vice president
            if ($vicePresidentCandidate) {
                ChosenPresidentVicePresidentStates::create([
                    'user_id' => auth()->id(),
                    'voter_candidate_id' => $vicePresidentCandidate->voter_candidate_id,
                    'votter_party_id' => $vicePresidentCandidate->votter_party_id,
                    'position' => 'vice_president',
                ]);
            }
        }

        //chosen candidate of selectionn in parties for president and vie presendent
        $CandidatesChosen = null;
        $chosenPartyCandidates = $this->chosenCandidates();

        if (!empty($chosenPartyCandidates)) {
            $CandidatesChosen = $chosenPartyCandidates;
        }

        //data of 2020 election
        $previousElection2020 = null;
        $dataOf2020 = $this->ElectionState2020();
        // return $dataOf2020;
        if (!empty($dataOf2020)) {
            $previousElection2020 = $dataOf2020;
        }

        // Form the response data
        $response = [
            'message' => 'Success',
            'data' => $partyCountsByState,
            'electoral_votes_by_party' => $electoralVotesByParty,
            'winning_candidates' => $candidates,
            'CandidatesChosen' => $CandidatesChosen,
            'previousElection2020' => $previousElection2020,
        ];

        return $this->sendSuccessResponse($response);
    }

    private function test($presidentCandidate, $vicePresidentCandidate)
    {
        // dd($presidentCandidate, $vicePresidentCandidate);
    }

    private function chosenCandidates()
    {
        $user = Auth::user();
        // dd($user);
        $userChosenCandidates = ChosenPartyCandidate::with('voter_candidate', 'votter_party')->where('user_id', $user->id)->get();
        $candidateChosens = $userChosenCandidates->map(function ($candidate) use ($userChosenCandidates) {
            return [
                'candidate_name' => $candidate->voter_candidate->candidate_name,
                'candidate_image' => $candidate->voter_candidate->candidate_image,
                'party_name' => $candidate->votter_party->party_name,
                'party_badge' => $candidate->votter_party->party_badge,
                'position' => $candidate->position,
            ];
        });
        return $candidateChosens;
    }
}
