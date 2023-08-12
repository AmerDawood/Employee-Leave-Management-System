<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('users')->paginate(10);

        return view('dashboard.admin.tasks.index',compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Make Check here
        // $users = User::all();

        $users = User::with('leaveRequests') // Eger Load
        ->whereDoesntHave('leaveRequests', function ($query) {
            $query->where('status', 'approved');
        })
        ->get();


        $otherUsers = User::with('leaveRequests')
        ->whereHas('leaveRequests', function ($query) {
            $query->where('status', 'approved');
        })
        ->get();



        return view('dashboard.admin.tasks.create', compact('users','otherUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'users' => 'required|array',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $task->users()->attach($request->users);

        return redirect()->route('tasks.index')->with('msg', 'Task created successfully.');
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
    public function edit(Task $task)
    {
        $users = User::all();
        $selectedUsers = $task->users->pluck('id')->toArray();

        return view('dashboard.admin.tasks.edit', compact('task', 'users','selectedUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'users' => 'required|array',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);


        $task->users()->sync($request->users);

        return redirect()->route('tasks.index')->with('msg', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->users()->detach();
        $task->delete();

        return redirect()->route('tasks.index')->with('msg', 'Task deleted successfully.');
    }
}
