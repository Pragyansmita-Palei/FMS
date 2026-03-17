<?php

namespace App\Http\Controllers;

use App\Models\GroupType;
use Illuminate\Http\Request;

class GroupTypeController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $query = GroupType::latest();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $groups = $query->paginate(10)->withQueryString();

        return view('group-types.index', compact('groups'));
    }

    // CREATE
    public function create()
    {
        return view('group-types.create');
    }

    // STORE
    public function store(Request $request)
    {
        // Validation: name required & unique
        $request->validate([
            'name' => 'required|unique:group_types,name',
        ]);

        GroupType::create([
            'name' => $request->name,
            'status' => $request->status ?? 0
        ]);

        return redirect()->route('group-types.index')
                         ->with('success', 'Group Type Added');
    }

    // EDIT
    public function edit($id)
    {
        $group = GroupType::findOrFail($id);
        return view('group-types.edit', compact('group'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $group = GroupType::findOrFail($id);

        // Validation: allow same name for this record, but not duplicates
        $request->validate([
            'name' => 'required|unique:group_types,name,' . $group->id,
        ]);

        $group->update([
            'name' => $request->name,
            'status' => $request->status ?? 0
        ]);

        return redirect()->route('group-types.index')
                         ->with('success', 'Group Type Updated');
    }

    // DELETE
    public function destroy($id)
    {
        GroupType::findOrFail($id)->delete();

        return redirect()->route('group-types.index')
                         ->with('success', 'Group Type Deleted');
    }

public function storeAjax(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    $group = GroupType::firstOrCreate(
        ['name' => $request->name],
        ['status' => 1]
    );

    return response()->json($group);
}

}
