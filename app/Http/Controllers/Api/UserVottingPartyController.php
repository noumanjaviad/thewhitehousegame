<?php

namespace App\Http\Controllers\Api;

use App\Models\VotterParty;
use Illuminate\Http\Request;
use App\Models\VoterCandidate;
use App\Models\UserPartyVoting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserVottingPartyController extends Controller {

    public function getVotterParties() {
        try {
            $votter_parties = VotterParty::all();
            $response = [
                'message' => 'sucess',
                'votter_parties' => $votter_parties,
            ];
            return $this->sendSuccessResponse( $response, 200 );
        } catch ( Exception $e ) {
            return $this->sendErrorResponse( 'internal server error', 500 );
        }
    }

    public function choseVotterParty( Request $request ) {
        try {
            $user = Auth::user();
            $user_id = $user->id;
            $choseparty = UserPartyVoting::create( [
                'user_id' => $user_id,
                'votter_party_id' => $request->votter_party_id,
                // 'postion' => $request->postion,
            ] );
            $response = [
                'message' => 'Party chosen successfully',
                'party_chose' => $choseparty,
            ];

            return $this->sendSuccessResponse( $response);
        } catch ( Exception $e ) {
            return $this->sendErrorResponse( 'Internal server error', 500 );
        }
    }
//this api will show the candidate base on chosen partychosen and show the remaining one
    public function getCandidateparty( Request $request, $id ) {
        try{
        // Get candidates belonging to the chosen party
        $candidates = VoterCandidate::where( 'votter_party_id', $id )->with( 'party' )->get();

        // Get the remaining parties
        $remaining_parties = VotterParty::whereNotIn( 'id', [ $id ] )->get();

        // Get candidates belonging to the remaining parties
        $remaining_candidates = [];
        foreach ( $remaining_parties as $party ) {
            $remaining_candidates[ $party->party_name ] = VoterCandidate::where( 'votter_party_id', $party->id )->with( 'party' )->get();
        }

        // return response()->json( [
        //     'candidates' => $candidates,
        //     'remaining_candidates' => $remaining_candidates
        // ] );

        $response=[
            'candidates' => $candidates,
            'remaining_candidates' => $remaining_candidates
        ];

        return $this->sendSuccessResponse($response);
    }catch(Exception $e){
        return $this->sendErrorResponse('internal service error',500);
    }
}

}
