<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{



    public function index(Request $request)
    {
        $status = $request->input('status');

        $query = LeaveRequest::with('user')
            ->orderByDesc('id');

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->where('status', '!=', 'approved');
        }

        $requests = $query->paginate(5);

        return view('dashboard.admin.requests.index', [
            'requests' => $requests,
            'selectedStatus' => $status,
        ]);
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
