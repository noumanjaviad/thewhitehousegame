<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VoterCandidate;
use Illuminate\Http\Request;

class VoterCandidateController extends Controller
{

    // befor chosing candidate for president and voice presendent in part this api will
    //start candidate with party with image

    // public function VoterCandidates()
    // {
    //     $candidates = VoterCandidate::with('party')->whereNotNull('order')
    //         ->orderBy('order', 'ASC')
    //         ->get();

    //     $kamalaHarris = null;
    //     $remainingCandidates = [];

    //     foreach ($candidates as $candidate) {
    //         // Clean the position field
    //         $candidate->position = str_replace('\\', '', $candidate->position);
    //         if (empty($candidate->position)) {
    //             $candidate->position = "No position specified";
    //         }

    //         // Check if the candidate is Kamala Harris
    //         if (strtolower($candidate->candidate_name) == 'kamala harris') {
    //             $kamalaHarris = $candidate;
    //         } else {
    //             $remainingCandidates[] = $candidate;
    //         }

    //     }
    //     // Sort the remaining candidates alphabetically by name
    //     usort($remainingCandidates, function ($a, $b) {
    //         return strcmp($a->candidate_name, $b->candidate_name);
    //     });

    //     // Combine Kamala Harris with the sorted candidates
    //     if ($kamalaHarris) {
    //         array_unshift($remainingCandidates, $kamalaHarris);
    //     }
    //     $response = [
    //         'message' => "success",
    //         'votter_candidate' => $candidates,
    //     ];

    //     return $this->sendSuccessResponse($response);
    // }

    public function VoterCandidates()
    {
        $candidates = VoterCandidate::with('party', 'parties')
            ->whereNotNull('order')
            ->orderBy('order', 'ASC')
            ->get();

        $kamalaHarris = null;
        $remainingCandidates = [];

        foreach ($candidates as $candidate) {
            // Clean the position field
            $candidate->position = str_replace('\\', '', $candidate->position);
            if (empty($candidate->position)) {
                $candidate->position = "No position specified";
            }
            if ($candidate->parties->isNotEmpty()) {
                $candidate->parties = $candidate->parties->first();
            }

            // Check if the candidate is Kamala Harris
            if (stripos($candidate->candidate_name, 'kamala harris') !== false) {
                $kamalaHarris = $candidate;
            } else {
                $remainingCandidates[] = $candidate;
            }
        }
        if ($kamalaHarris) {
            array_unshift($remainingCandidates, $kamalaHarris);
        }

        $response = [
            'message' => "success",
            'votter_candidate' => $remainingCandidates,
        ];

        return $this->sendSuccessResponse($response);
    }

    //end

    public function voterCandidateList()
    {
        // dd(12);
        try {
            $candidate = VoterCandidate::select('id', 'candidate_name', 'candidate_image')->get();
            $response = [
                'message' => "success",
                'votter_candidate_list' => $candidate,
            ];
            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }

    //show candidate base on id
    public function getCandidate(Request $request, $id)
    {
        try {
            $candidate = VoterCandidate::select('id', 'candidate_name', 'candidate_image', 'dob', 'occupation', 'position')->find($id);
            if (!$candidate) {
                return $this->sendErrorResponse('Candidate not found', 404);
            }
            $positions = json_decode($candidate->position, true);

            // If there's an error decoding JSON, return appropriate error response
            if ($positions === null && json_last_error() !== JSON_ERROR_NONE) {
                return $this->sendErrorResponse('Invalid JSON format in position field', 500);
            }

            // Implode the array to get a concatenated string
            $positionsString = implode(", ", $positions);

            // Add the concatenated string to the candidate object
            $candidate->positions = $positionsString;

            // Remove the original position field from the candidate object
            unset($candidate->position);
            // dd($candidate);
            $response = [
                'message' => 'success',
                'candidate' => $candidate,
            ];
            return $this->sendSuccessResponse($response);
        } catch (Exception $e) {
            return $this->sendErrorResponse('internal server error', 500);
        }
    }

    //start this api is for storing VoterCandidatedetail in db not use in hmid side
    public function VoterCandidateDetails(Request $request)
    {
        $candidate = new VoterCandidate();
        $request->validate([
            'candidate_image' => 'image|mimes:jpeg,png,jpg,gif|max:6000', // Allow up to 5000 kilobytes (or 5 megabytes)
            'candidate_name' => 'required',
            'votter_party_id' => 'required',
        ]);
        if ($request->hasFile('candidate_image')) {
            $imagePath = $request->file('candidate_image')->store('candidate_images', 'public'); // Adjust storage path as needed
            $candidate->candidate_image = 'storage/' . $imagePath;
        }

        $candidate->candidate_name = $request->input('candidate_name');
        $candidate->votter_party_id = $request->input('votter_party_id');

        $candidate->save();

        return response()->json(['message' => 'Candidate create successfully', 'data' => $candidate]);
    }
    //end

    //update votter candidate record that is dob,position and occupation not use on hmid side
    //its just storing VoterCandidate detail in db.
    public function updateVoterCandidate(Request $request, $id)
    {
        // dd($id);
        try {
            $candidate = VoterCandidate::findOrFail($id);
            // dd($candidate);
            $candidate->update($request->only([
                'dob',
                'birth_place',
                'occupation',
                'position',
            ]));
            $response = [
                'message' => 'voter candidate update successfully',
                'voter_candidate' => $candidate,
            ];
            return $this->sendSuccessResponse($response);

        } catch (Exception $e) {
            return $this->sendErrorResponse('internal servererror', 500);
        }
    }
}
