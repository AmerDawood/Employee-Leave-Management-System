<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{



    public function index()
    {
        $requests = LeaveRequest::with('user') // Eager load
                                ->orderByDesc('id')
                                ->where('status','!=','approved')
                                ->get();
                                
        return view('dashboard.admin.requests.index', ['requests' => $requests]);
    }


    public function show($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        return view('dashboard.admin.requests.status', ['leaveRequest' => $leaveRequest]);
    }



    public function updateLeaveRequest(Request $request, LeaveRequest $leaveRequest)
{
    $request->validate([
        'status' => 'required|in:pending,approved,rejected',
        'reply' => 'nullable|string',
    ]);

    $leaveRequest->update([
        'status' => $request->status,
        'reply' => $request->reply,
    ]);

    return redirect()->route('dashboard.index')->with('success', 'Leave request updated successfully.');

}



}
