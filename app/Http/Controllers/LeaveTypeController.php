<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = LeaveType::orderbyDesc('id')->paginate(5);
        return view('dashboard.admin.leave_types.index',
     [
        'types'=>$types
     ]
    );
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'description' =>'required',
        ]);

        LeaveType::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        // toastr()->success('Leave Type Created Success');


        return redirect()->route('leave-types.index')->with('msg','Leave Type Created Success');


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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $type)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'nullable',
            'description' => 'nullable',
        ]);

        $type = LeaveType::findOrFail($request->leave_type);
        
        $type->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);


        return redirect()->route('leave-types.index')
            ->with('msg', 'Leave Type updated successfully')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $type = LeaveType::findOrFail($id);

        $type->delete();

        return redirect()->route('leave-types.index')->with('msg', 'Leave Type Deleted Successfully')->with('type', 'danger');
    }




    public function getLeaveRequestByLeaveType($id)
    {
        $requests = LeaveRequest::where('leave_type_id',$id)->orderByDesc('id')->get();

        return view('dashboard.admin.leave_types.requests',['requests'=>$requests]);
    }
}
