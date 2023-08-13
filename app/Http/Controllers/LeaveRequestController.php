<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $user_id = auth()->user()->id;
        $leaveType = LeaveType::all();
        $requests = LeaveRequest::with('leaveType') // Eager load
                                ->where('user_id', $user_id)
                                ->orderByDesc('id')
                                ->get();


        return view('dashboard.employee.all_requests', ['requests' => $requests,'leaveType'=>$leaveType]);

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
        $user = auth()->user();
        $userTasksCount = $user->tasks->count();

        if ($userTasksCount > 3) {
            return redirect()->route('my-requests.index')->with('msg', 'You cannot create a leave request because you already have more than 3 tasks.')->with('type','danger');
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);

        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('my-requests.index')->with('msg', 'Leave Request Created Successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        return view('dashboard.employee.show', ['leaveRequest' => $leaveRequest]);
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);

        $leaveRequest->update([
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('my-requests.index')->with('msg', 'Leave request updated successfully.');
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $request = LeaveRequest::findOrFail($id);

        $request->delete();

        return redirect()->route('my-requests.index')->with('msg', 'Leave Request Deleted Successfully');
    }








    ///





}
