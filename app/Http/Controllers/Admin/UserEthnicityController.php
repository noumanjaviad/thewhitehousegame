<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserEthnicity;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class UserEthnicityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.userethnicity.show_user_ethnicity');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.userethnicity.create_user_ethnicity');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ethnicity = UserEthnicity::create(['name' => $request->name]);
        return redirect('admin/ethnicity')->with('Sucess', 'Ethnicities create sucessfully');
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
        $ethnicity = UserEthnicity::where('id',$id)->first();
        return view('Admin.userethnicity.create_user_ethnicity',compact('ethnicity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ethnicity = UserEthnicity::findOrFail($id);
        $ethnicity->update(['name'=>$request->name]);
        return redirect('admin/ethnicity')->with('Sucess','Ethnicity Updated Sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function ethnicity_delete(Request $request)
    {
        $ethnicity = UserEthnicity::where('id', $request->id)->first();
        if ($ethnicity->delete()) {
            return response()->json([
                'message' => 'user Ethnicity  delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'User Ethnicity does not exist',
            ], 403);
        }
    }
    public function get_all_ethnicity(Request $request)
    {
        if ($request->ajax()) {
            $ethnicity = UserEthnicity::all();
            return DataTables::of($ethnicity)
                ->addIndexColumn()
                ->addColumn('action', function ($ethnicity) {
                    return view('Admin.userethnicity.action_user_ethnicity', compact('ethnicity'))->render();
                })
                ->addColumn('name', function ($ethnicity) {
                    return ucfirst($ethnicity->name);
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
        }
    }
}
