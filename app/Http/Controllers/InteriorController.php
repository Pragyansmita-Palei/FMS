<?php

namespace App\Http\Controllers;

use App\Models\Interior;
use Illuminate\Http\Request;

class InteriorController extends Controller
{

public function index(Request $request)
{
    $query = Interior::query();

    if ($request->search) {
        $query->where('firm_name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%');
    }

    $interiors = $query->latest()->paginate(10);

    return view('interiors.index', compact('interiors'));
}
    // CREATE FORM
    public function create()
    {
        return view('interiors.create');
    }

    // STORE DATA
 public function store(Request $request)
{
    $validated = $request->validate([
        'firm_name' => 'required|string|max:255',
        'email'     => 'nullable|email|max:255',
        'phone'     => 'nullable|string|max:15',
        'address'   => 'nullable|string',
    ]);

    Interior::create($validated);

    return redirect()->route('interiors.index');
}

    // EDIT FORM
    public function edit($id)
    {
        $interior = Interior::findOrFail($id);
        return view('interiors.edit', compact('interior'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $interior = Interior::findOrFail($id);

        $request->validate([
            'firm_name' => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|digits_between:8,15',
            'address'   => 'nullable|string',
        ]);

        $interior->update($request->all());

        return redirect()
            ->route('interiors.index', $id);
    }

    public function destroy($id)
{
    $interior = Interior::findOrFail($id);
    $interior->delete();

    return redirect()->route('interiors.index')
                     ->with('success', 'Interior deleted successfully.');
}

public function assignInterior(Request $request, Project $project)
{
    $request->validate([
        'interior_id' => 'required|exists:interiors,id'
    ]);

    $project->interior_id = $request->interior_id;
    $project->save();

    return response()->json(['success' => true]);
}


}