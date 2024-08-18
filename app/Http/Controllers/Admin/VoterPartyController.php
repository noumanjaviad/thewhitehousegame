<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotterParty;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VoterPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.voterparty.show_voter_party');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.voterparty.create_voter_party');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image_url = '';

        if ($request->hasFile('party_badge') && $request->file('party_badge')->isValid()) {
            $imagePath = $request->file('party_badge')->store('party_badge_image', 'public');
            $image_url = 'storage/' . $imagePath;
        }
        $voter_party = VotterParty::create([
            "party_name" => $request->name,
            "party_badge" => $image_url,
        ]);

        // Redirect with success message
        return redirect('admin/parties')->with('success', 'Voter party created successfully.');
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
        $voter_party = VotterParty::where('id', $id)->first();
        return view('Admin.voterparty.create_voter_party', compact('voter_party'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $image_url = '';
            $voter_party = VotterParty::findOrFail($id);

            if ($request->hasFile('party_badge') && $request->file('party_badge')->isValid()) {
                $imagePath = $request->file('party_badge')->store('party_badge_image', 'public');
                $image_url = 'storage/' . $imagePath;
            }
            $voter_party->update([
                'party_name' => $request->name,
                'party_badge' => $image_url,
            ]);

            return redirect('admin/parties')->with('success', 'Voter party updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendErrorResponse('Voter party not found', 404);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Internal server error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function party_delete(Request $request)
    {
        $voter_party = VotterParty::where('id', $request->id)->first();
        if ($voter_party->delete()) {
            return response()->json([
                'message' => 'voter party delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'voter does not exist',
            ], 403);
        }
    }

    public function test(Request $request)
    {
        if ($request->ajax()) {
            $voter_party = VotterParty::all();
            // dd($voter_party);
            return DataTables::of($voter_party)
                ->addIndexColumn()
                ->addColumn('action', function ($voter_party) {
                    return view('Admin.voterparty.action_voter_party', compact('voter_party'))->render();
                })
                ->addColumn('name', function ($voter_party) {
                    return ucfirst($voter_party->party_name);
                })
                ->addColumn('party_badge', function ($voter_party) {
                    return '<img src="' . asset($voter_party->party_badge) . '"width="30">';
                })
                ->rawColumns(['action', 'name', 'party_badge'])
                ->make(true);
        }
    }
}
