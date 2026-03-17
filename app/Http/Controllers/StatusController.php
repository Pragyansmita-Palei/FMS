<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Role;

class StatusController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $statuses = Status::with('role')->latest()->get();

        return view('statuses.index', compact('roles', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        Status::create([
            'name' => $request->name,
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()->with('success', 'Status added successfully.');
    }

    public function update(Request $request, $id)
    {
        $status = Status::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $status->update([
            'name' => $request->name,
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();

        return redirect()->back()->with('success', 'Status deleted successfully.');
    }

public function getStatusByRole(Request $request)
{
    $role = Role::where('name', $request->role)->first();

    if (!$role) {
        return response()->json([]);
    }

    $statuses = Status::where('role_id', $role->id)->get();

    return response()->json($statuses);
}
}
