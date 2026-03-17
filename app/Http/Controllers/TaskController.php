<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;

class TaskController extends Controller
{

    /* ===============================
        INDEX
    =============================== */
   public function index()
{
    $user = Auth::user();

    $tasksQuery = Task::with([
        'assignedUsers:id,name',
        'project:id,project_name'
    ])->latest();

    // ✅ Only restrict by assigned user
    if (!$user->hasRole('admin')) {
        $tasksQuery->whereHas('assignedUsers', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }

    $tasks = $tasksQuery->paginate(10);
    $projects = Project::select('id', 'project_name')->get();
    $roles = Role::where('name', '!=', 'admin')->get();

    return view('tasks.index', compact('tasks','projects','roles'));
}

    /* ===============================
        STORE
    =============================== */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'assignments' => 'required|array'
        ]);

        // Collect selected users
        $userIds = collect($request->assignments)
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values();

        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'project_id'  => $request->project_id,
            'due_date'    => $request->due_date,
            'due_time'    => $request->due_time
                ? date('H:i:s', strtotime($request->due_time))
                : null,
            'priority'    => $request->priority,
            'status'      => $request->status,
            'assigned_user_id' => $userIds->first(),
        ]);

        if ($userIds->isNotEmpty()) {
            $task->assignedUsers()->sync($userIds);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task Added Successfully');
    }

    /* ===============================
        UPDATE
    =============================== */
   public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);
    $user = auth()->user();

    // 🔥 Inline status update
    if ($request->has('status') && $request->keys() === ['_token', '_method', 'status']) {

        // ✅ If Admin → directly update
        if ($user->hasRole('admin')) {

            $task->status = $request->status;
            $task->requested_status = null; // clear pending
            $task->save();

            return back()->with('success', 'Status updated');

        } else {

            // ✅ If NOT Admin → store as requested_status
            $task->requested_status = $request->status;
            $task->save();

            return back()->with('success', 'Status sent for approval');
        }
    }

    // 🔥 Normal modal edit (keep your existing logic)
    $data = [
        'title'       => $request->title,
        'description' => $request->description,
        'project_id'  => $request->project_id,
        'due_date'    => $request->due_date,
        'due_time'    => $request->due_time
            ? date('H:i:s', strtotime($request->due_time))
            : null,
        'priority'    => $request->priority,
        'status'      => $request->status,
    ];

    $task->update($data);

    if ($request->has('assignments')) {
        $userIds = collect($request->assignments)
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values();

        $task->assignedUsers()->sync($userIds);
    }

    return redirect()->route('tasks.index')
        ->with('success', 'Task Updated Successfully');
}

public function approve($id)
{
    $task = Task::findOrFail($id);

    if (!$task->requested_status) {
        return back();
    }

    $task->status = $task->requested_status;
    $task->requested_status = null;
    $task->save();

    return back()->with('success', 'Status Approved Successfully');
}

    /* ===============================
        DELETE
    =============================== */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task Deleted Successfully');
    }

    /* ===============================
        FETCH USERS BY ROLE (AJAX)
    =============================== */
    public function getUsersByRoles(Request $request)
    {
        $roles = $request->roles;

        $users = User::whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        })->get();

        return response()->json($users);
    }
}
