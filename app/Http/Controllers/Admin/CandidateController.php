<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoterCandidate;
use App\Models\VotterParty;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('Admin.candidate.show_candidate');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $cadidate=VoterCandidate::all();
        $votter_party = VotterParty::all();
        return view('Admin.candidate.create_candidates', compact('votter_party'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $imagePath = null;

        if ($request->hasFile('candidate_image')) {
            $imagePath = $request->file('candidate_image')->store('candidate_images', 'public'); // Adjust storage path as needed
        }

        $candidate = VoterCandidate::create([
            'candidate_name' => $request->name,
            'dob' => $request->dob,
            'candidate_image' => $imagePath ? 'storage/' . $imagePath : null,
            'birth_place' => $request->birth_place,
            'occupation' => $request->occupation,
            'occupation_1' => $request->occupation_1,
            'position' => $request->position,
            'position_1' => $request->position_1,
            'votter_party_id' => $request->votter_party_id,
            'order' => $request->order,
        ]);
        return redirect('admin/candidate')->with('success', 'Candidate created successfully.');
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
        $candidate = VoterCandidate::where('id', $id)->first();
        return view('Admin.candidate.create_candidates', compact('votter_party', 'candidate'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request,$id)
    // {
    //     $candidate = VoterCandidate::findOrFail($id);

    //     $candidate->candidate_name = $request->name;
    //     // $candidate->dob = $request->dob;
    //     $candidate->birth_place = $request->birth_place;
    //     $candidate->occupation = $request->occupation;
    //     $candidate->position = $request->position;
    //     // $candidate->votter_party_id = $request->votter_party_id;

    //     if ($request->hasFile('candidate_image')) {

    //         $imagePath = $request->file('candidate_image')->store('candidate_images', 'public');
    //         $candidate->candidate_image = 'storage/' . $imagePath;
    //     }
    //     $candidate->save();
    //     return redirect('admin/candidate')->with('success', 'Candidate updated successfully.');

    // }

    public function update(Request $request, $id)
    {
        // Find the candidate
        $candidate = VoterCandidate::findOrFail($id);

        // Update the candidate fields
        $candidate->candidate_name = $request->name;
        $candidate->birth_place = $request->birth_place;
        $candidate->occupation = $request->occupation;
        $candidate->occupation_1 = $request->occupation_1;

        // Convert the position field to JSON before saving
        $candidate->position = $request->position;
        $candidate->position_1 = $request->position_1;

        // Handle file upload for candidate image
        if ($request->hasFile('candidate_image')) {
            $imagePath = $request->file('candidate_image')->store('candidate_images', 'public');
            $candidate->candidate_image = 'storage/' . $imagePath;
        }

        // Save the candidate
        $candidate->save();

        $candidates = VoterCandidate::findOrFail($id);
        // Redirect with success message
        return redirect('admin/candidate')->with('success', 'Candidate updated successfully.',compact('candidates'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function candidate_delete(Request $request)
    {
        $candidate = VoterCandidate::where('id', $request->id)->first();
        if ($candidate->delete()) {
            return response()->json([
                'message' => 'candidate delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'candidate does not exist',
            ], 403);
        }
    }

    public function get_all_candidates(Request $request)
    {
        // dd(12);
        if ($request->ajax()) {
            $candidate = VoterCandidate::with('party')->whereNot('order', 'null')
                ->orderBy('order', 'ASC')
                ->get();
            // dd($candidate);
            return DataTables::of($candidate)
                ->addIndexColumn()
                ->addColumn('candidate_image', function ($candidate) {
                    return '<img src="' . asset($candidate->candidate_image) . '"width="30">';
                })
                ->addColumn('candidate_name', function ($candidate) {
                    return $candidate->candidate_name;
                })
                ->addColumn('dob', function ($candidate) {
                    return ucfirst($candidate->dob);
                })
                ->addColumn('votter_party_id', function ($candidate) {
                    return $candidate->party->party_name;
                })
                ->addColumn('order', function ($candidate) {
                    return $candidate->order;
                })
            // ->addColumn('votter_party_name', function ($candidate) {
            //     $votter_party = VotterParty::find($candidate->votter_party_id);
            //     return $votter_party ? $votter_party->name : 'Unknown';
            // })

                ->addColumn('action', function ($candidate) {
                    return view('Admin.candidate.action_candidates', compact('candidate'))->render();
                })
                ->rawColumns(['candidate_image', 'candidate_name', 'votter_party_name', 'dob', 'action'])
                ->make(true);
        }
    }
}
