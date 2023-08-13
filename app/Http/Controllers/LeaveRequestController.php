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
                                ->paginate(7);


        return view('dashboard.employee.all_requests', ['requests' => $requests,'leaveType'=>$leaveType]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $userTasksCount = $user->tasks->count();

        if ($userTasksCount > 3) {
            return redirect()->route('my-requests.index')->with('msg', 'You cannot create a leave request because you already have more than 3 tasks.')->with('type', 'danger');
        }

        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);

        $leaveTypeId = $request->input('leave_type_id');
        $manualLeaveType = $request->input('manual_leave_type');

        if ($leaveTypeId === 'other' && empty($manualLeaveType)) {
            // Set manual_leave_type as null
            $manualLeaveType = null;
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type_id' => $leaveTypeId === 'other' ? null : $leaveTypeId,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'reason' => $request->input('reason'),
            'manual_leave_type' => $manualLeaveType,
        ]);

        return redirect()->route('my-requests.index')->with('msg', 'Leave Request Created Successfully');
    }




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

        $user = auth()->user();
        $userTasksCount = $user->tasks->count();

        if ($userTasksCount > 3) {
            return redirect()->route('my-requests.index')->with('msg', 'You cannot update the leave request because you already have more than 3 tasks.')->with('type', 'danger');
        }

        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ]);

        $leaveTypeId = $request->input('leave_type_id');
        $manualLeaveType = $request->input('manual_leave_type');

        if ($leaveTypeId === 'other' && empty($manualLeaveType)) {
            return redirect()->route('my-requests.index')->with('msg', 'Leave Request not updated. Select a valid leave type or provide a manual leave type.')->with('type', 'danger');
        }

        $leaveRequest->update([
            'leave_type_id' => $leaveTypeId === 'other' ? null : $leaveTypeId,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'reason' => $request->input('reason'),
            'manual_leave_type' => $leaveTypeId === 'other' ? $manualLeaveType : null,
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
