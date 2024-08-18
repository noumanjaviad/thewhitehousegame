<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserState;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        return view('Admin.state.show_state');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('Admin.state.create_state');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request->all());
        $image_url = null;
        $state_map_image = null;
        $state_image_url = null;

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('state_flag_image', 'public');
            $image_url = 'storage/' . $imagePath;
        }

        if ($request->hasFile('state_map_image')) {
            $mapImagePath = $request->file('state_map_image')->store('state_images', 'public');
            $state_map_image = 'storage/' . $mapImagePath;
        }

        if ($request->hasFile('state_image_url')) {
            $stateImagePath = $request->file('state_image_url')->store('state_images', 'public');
            $state_image_url = 'storage/' . $stateImagePath;
        }

        $state = UserState::create([
            'name' => $request->name,
            'image_url' => $image_url,
            'map_url' => $state_map_image,
            'state_image_url' => $state_image_url,
        ]);
        // dd($state);

        if (!$state) {
            return redirect('admin/state')->with('error', 'Failed to create state.');
        }

        return redirect('admin/state')->with('success', 'State created successfully.');
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
        $state=UserState::where('id',$id)->first();
        return view('Admin.state.create_state',compact('state'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $state = UserState::findOrFail($id);

        $image_url = $state->image_url;
        $state_map_image = $state->map_url;
        $state_image_url = $state->state_image_url;

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('state_flag_image', 'public');
            // dd($imagePath);
            $image_url = 'storage/' . $imagePath;
            // dd($image_url);
        }

        if ($request->hasFile('state_map_image')) {
            $mapImagePath = $request->file('state_map_image')->store('state_images', 'public');
            $state_map_image = 'storage/' . $mapImagePath;
        }

        if ($request->hasFile('state_image_url')) {
            $stateImagePath = $request->file('state_image_url')->store('state_images', 'public');
            $state_image_url = 'storage/' . $stateImagePath;
        }

        $state->update([
            'name' => $request->name,
            'image_url' => $image_url,
            'map_url' => $state_map_image,
            'state_image_url' => $state_image_url,
        ]);

        if (!$state) {
            return redirect()->back()->with('error', 'Failed to update state.');
        }

        return redirect('admin/state')->with('success', 'State updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
    }

    public function state_delete(Request $request)
    {
        $state = UserState::where('id', $request->id)->first();
        if ($state->delete()) {
            return response()->json([
                'message' => 'state delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'voter does not exist',
            ], 403);
        }
    }

    public function get_all_states(Request $request)
    {
        if ($request->ajax()) {
            $state = UserState::all();
            return DataTables::of($state)
                ->addIndexColumn()
                ->addColumn('action', function ($state) {
                    return view('Admin.state.action_state', compact('state'))->render();
                })
                ->addColumn('name', function ($state) {
                    return ucfirst($state->name);
                })
                ->addColumn('image_url', function ($state) {
                    return '<img src="' . asset($state->image_url) . '"width="30">';
                })
                ->addColumn('map_url', function ($state) {
                    return '<img src="' . asset($state->map_url) . '"width="30">';
                })
                ->addColumn('state_image_url', function ($state) {
                    return '<img src="' . asset($state->state_image_url) . '"width="30">';
                })
                ->rawColumns(['action', 'name', 'image_url', 'map_url', 'state_image_url'])
                ->make(true);
        }
    }
}
