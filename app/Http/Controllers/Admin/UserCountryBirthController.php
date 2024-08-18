<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCountryBirth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserCountryBirthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.usercountrybirth.show_user_country_birth');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.usercountrybirth.create_user_country_birth');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ucb = UserCountryBirth::create(['name' => $request->name]);
        return redirect('admin/ucb')->with('Sucess', 'User Country Birth Successfully');

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
        $ucb = UserCountryBirth::where('id', $id)->first();
        return view('Admin.usercountrybirth.create_user_country_birth', compact('ucb'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ucb = UserCountryBirth::find($id);
        $ucb->update(['name' => $request->name]);
        return redirect('admin/ucb')->with('Sucess', 'User Country birth update sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function ucb_delete(Request $request)
    {
        $ucb = UserCountryBirth::where('id', $request->id)->first();
        if ($ucb->delete()) {
            return response()->json([
                'message' => 'user country birth delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'user country birth does not exist',
            ], 403);
        }
    }
    public function get_all_ucb(Request $request)
    {
        if ($request->ajax()) {
            $ucb = UserCountryBirth::all();
            return DataTables::of($ucb)
                ->addIndexColumn()
                ->addColumn('action', function ($ucb) {
                    return view('Admin.usercountrybirth.action_user_country_birth', compact('ucb'))->render();
                })
                ->addColumn('name', function ($ucb) {
                    return ucfirst($ucb->name);
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
        }
    }

}
