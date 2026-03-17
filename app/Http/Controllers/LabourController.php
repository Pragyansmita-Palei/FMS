<?php

namespace App\Http\Controllers;

use App\Models\Labour;
use Illuminate\Http\Request;

class LabourController extends Controller
{
    public function create()
    {
        return view('labours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'labour_name'  => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email'        => 'nullable|email|max:255',
            'address'      => 'required|string',

            'rate_type'    => 'required|in:day,hour',
            'price'        => 'required|numeric|min:0',
        ]);

        Labour::create($validated);

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour added successfully');
    }

    public function index(Request $request)
    {
        $query = Labour::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('labour_name', 'like', '%'.$request->search.'%')
                  ->orWhere('phone_number', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $labours = $query->latest()->paginate(10)->withQueryString();

        return view('labours.index', compact('labours'));
    }

    public function edit($id)
    {
        $labour = Labour::findOrFail($id);

        return view('labours.edit', compact('labour'));
    }

    public function update(Request $request, $id)
    {
        $labour = Labour::findOrFail($id);

        $validated = $request->validate([
            'labour_name'  => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email'        => 'nullable|email|max:255',
            'address'      => 'required|string',

            'rate_type'    => 'required|in:day,hour',
            'price'        => 'required|numeric|min:0',
        ]);

        $labour->update($validated);

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour updated successfully');
    }

    public function destroy($id)
    {
        $labour = Labour::findOrFail($id);
        $labour->delete();

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour deleted successfully');
    }
}