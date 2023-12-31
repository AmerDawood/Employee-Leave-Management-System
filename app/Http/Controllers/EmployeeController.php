<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $users = User::when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', '%' . $query . '%')
                             ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->orderByDesc('id')
            ->paginate(5);

        return view('dashboard.admin.employee.index', ['users' => $users, 'query' => $query]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            // 'type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

       User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            // 'type' => $request->input('type'),

        ]);

        return redirect()->route('employees.index')->with('msg', 'Employee Created Successfully')->with('type', 'success');
    }



    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:6',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
    ]);

    return redirect()->route('employees.index')->with('msg', 'Employee Updated Successfully')->with('type', 'success');
}




    public function destroy(string $id)
    {
        $request = User::findOrFail($id);

        $request->delete();

        return redirect()->route('employees.index')->with('msg', 'Employee Deleted Successfully')->with('type', 'danger');
    }



    public function getLeaveRequestByUserId($id)
    {

        $requests = LeaveRequest::where('user_id','=',$id)->get();

        return view('dashboard.admin.employee.employee_request',compact('requests'));


    }




    // Tasks



    public function tasksByCurrentUser()
    {
        $user = auth()->user();

        $tasks = $user->tasks;

        // dd($tasks);

        return view('dashboard.employee.tasks.index', ['tasks' => $tasks]);
    }


    public function leave(Request $request, Task $task)
{
    $user = auth()->user();

    $task->users()->detach($user->id);

    return redirect()->route('tasks.myTasks')->with('msg', 'You left the task successfully.');
}




public function show(Task $task)
{
    $task->load('users');

    return view('dashboard.employee.tasks.show', compact('task'));
}


}
