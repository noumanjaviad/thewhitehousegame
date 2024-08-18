<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreviousElectionState;
use App\Models\UserState;
use App\Models\VotterParty;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PreviousElectionStateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.previouselectionstate.show_previous_election_state');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $votter_party = VotterParty::all();
        $states = UserState::all();
        return view('Admin.previouselectionstate.create_previous_election_state', compact('votter_party', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $previous_election = PreviousElectionState::create(
            [
                'votter_party_id' => $request->votter_party_id,
                'user_state_id' => $request->user_state_id,
                'election_year' => $request->election_year,
                'age_range' => $request->age_range,
                'vote_percentage' => $request->voter_percentage,
                'male_ratio' => $request->male_ratio,
                'female_ratio' => $request->female_ratio,
            ]);
            // dd($previous_election);

        return redirect('admin/previous_election')->with('Sucess', 'Previous election record is added sucessfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $votter_party = VotterParty::all();
        $states = UserState::all();
        $previous_election = PreviousElectionState::findOrFail($id);
        return view('Admin.previouselectionstate.create_previous_election_state', compact('votter_party', 'states', 'previous_election'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $previous_election = PreviousElectionState::findOrFail($id);

        $previous_election->update(
            [
                'votter_party_id' => $request->votter_party_id,
                'user_state_id' => $request->user_state_id,
                'election_year' => $request->election_year,
                'age_range' => $request->age_range,
                'voter_percentage' => $request->voter_percentage,
                'male_ratio' => $request->male_ratio,
                'female_ratio' => $request->female_ratio,
            ]);

        return redirect('admin/previous_election')->with('Sucess', 'Previous election record is updated sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function previous_election_delete(Request $request)
    {
        $previous_election = PreviousElectionState::where('id', $request->id)->first();
        if ($previous_election->delete()) {
            return response()->json([
                'message' => 'Previous election data delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Previous election does not exist',
            ], 403);
        }
    }

    public function get_all_previous_election(Request $request)
    {
        if ($request->ajax()) {
            $previous_elections = PreviousElectionState::with('votter_party', 'user_state')->get();
            // dd($previous_elections);
            return DataTables::of($previous_elections)
                ->addColumn('party_name', function ($previous_election) {
                    return $previous_election->votter_party ? $previous_election->votter_party->party_name : 'Unknown Party';

                })
                ->addColumn('state_name', function ($previous_election) {
                    return $previous_election->user_state ? $previous_election->user_state->name : 'Unknown State';
                })
                ->addColumn('action', function ($previous_election) {
                    return view('Admin.previouselectionstate.action_previous_election_state', compact('previous_election'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

}
