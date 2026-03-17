<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{

   public function index(Request $request)
{
    $query = TermCondition::query();

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }

    $terms = $query->latest()->paginate(10);

    return view('admin.terms.index', compact('terms'));
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        TermCondition::create([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return back()->with('success','Term added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $term = TermCondition::findOrFail($id);

        $term->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return back()->with('success','Term updated successfully');
    }

    public function destroy($id)
    {
        TermCondition::findOrFail($id)->delete();
        return back()->with('success','Deleted successfully');
    }

}
