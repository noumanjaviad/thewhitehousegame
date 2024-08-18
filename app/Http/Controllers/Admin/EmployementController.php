<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserEmployement;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class EmployementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.employement.show_user_ethnicity');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.employement.create_employement');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employement = UserEmployement::create(['employement_status' => $request->name]);
        return redirect('admin/employement')->with('Sucess', 'Employement created Sucessfully');
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
        $employement = UserEmployement::where('id',$id)->first();
        return view('Admin.employement.create_employement',compact('employement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employement = UserEmployement::findOrFail($id);
        $employement->update(['employement_status'=>$request->name]);
        return redirect('admin/employement');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function employement_delete(Request $request)
    {
        $employement = UserEmployement::where('id', $request->id)->first();
        if ($employement->delete()) {
            return response()->json([
                'message' => 'Employement status  delete successfully',
                'code' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Employement does not exist',
            ], 403);
        }
    }
    public function get_all_employement(Request $request)
    {
        if ($request->ajax()) {
            $employement = UserEmployement::all();
            return DataTables::of($employement)
                ->addIndexColumn()
                ->addColumn('action', function ($employement) {
                    return view('Admin.employement.action_employement', compact('employement'))->render();
                })
                ->addColumn('name', function ($employement) {
                    return ucfirst($employement->employement_status);
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
        }
    }
}
