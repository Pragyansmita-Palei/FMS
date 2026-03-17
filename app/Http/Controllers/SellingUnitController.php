<?php

namespace App\Http\Controllers;

use App\Models\SellingUnit;
use App\Models\GroupType;
use Illuminate\Http\Request;

class SellingUnitController extends Controller
{
    // INDEX
   public function index(Request $request)
{
    $query = SellingUnit::with('groupType');

    if ($request->filled('search')) {
        $query->where('unit_name', 'like', '%'.$request->search.'%');
    }

    $units = $query->paginate(10)->withQueryString();
    $groupTypes = GroupType::all();

    return view('selling-units.index', compact('units','groupTypes'));
}

    // CREATE
    public function create()
    {
        $groupTypes = GroupType::all();
        return view('selling-units.create', compact('groupTypes'));
    }

    // STORE
  public function store(Request $request)
{
    $request->merge([
        'unit_name' => trim($request->unit_name)
    ]);

    $request->validate([
        'group_type_id' => 'required',
        'unit_name'     => 'required'
    ]);

    // Split input
    $newUnits = array_map('trim', explode(',', strtolower($request->unit_name)));

    // Get existing units for group
    $existing = SellingUnit::where('group_type_id', $request->group_type_id)->get();

    foreach ($existing as $row) {
        $oldUnits = array_map('trim', explode(',', strtolower($row->unit_name)));

        // check intersection
        if (array_intersect($newUnits, $oldUnits)) {
            return back()
                ->withErrors([
                    'unit_name' => 'One or more units already exist in this group.'
                ])
                ->withInput();
        }
    }

    // Remove duplicate inside input itself
    $final = implode(',', array_unique($newUnits));

    SellingUnit::create([
        'group_type_id' => $request->group_type_id,
        'unit_name'     => $final,
        'status'        => 0
    ]);

    return redirect()
        ->route('selling-units.index')
        ->with('success', 'Selling Unit Added');
}

    // EDIT
    public function edit($id)
    {
        $unit = SellingUnit::findOrFail($id);
        $groupTypes = GroupType::all();

        return view('selling-units.edit', compact('unit','groupTypes'));
    }

    // UPDATE
   public function update(Request $request, $id)
{
    $request->merge([
        'unit_name' => trim($request->unit_name)
    ]);

    $request->validate([
        'group_type_id' => 'required',
        'unit_name'     => 'required'
    ]);

    // Split new input
    $newUnits = array_map('trim', explode(',', strtolower($request->unit_name)));

    // Get existing records EXCEPT current
    $existing = SellingUnit::where('group_type_id', $request->group_type_id)
        ->where('id', '!=', $id)
        ->get();

    foreach ($existing as $row) {
        $oldUnits = array_map('trim', explode(',', strtolower($row->unit_name)));

        // Check duplicates
        if (array_intersect($newUnits, $oldUnits)) {
            return back()
                ->withErrors([
                    'unit_name' => 'One or more units already exist in this group.'
                ])
                ->withInput();
        }
    }

    // Remove duplicate inside input itself
    $final = implode(',', array_unique($newUnits));

    $unit = SellingUnit::findOrFail($id);

    $unit->update([
        'group_type_id' => $request->group_type_id,
        'unit_name'     => $final,
        'status'        => 0
    ]);

    return redirect()
        ->route('selling-units.index')
        ->with('success', 'Selling Unit Updated');
}


    // DELETE
    public function destroy($id)
    {
        SellingUnit::findOrFail($id)->delete();

        return redirect()
            ->route('selling-units.index')
            ->with('success', 'Selling Unit Deleted');
    }
}
