<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'branch_name' => 'required|string|max:255',
            'branch_contact_name' => 'required|string|max:255',
            'branch_contact_phone' => 'required|string|max:20',
            'branch_contact_email' => 'nullable|email',
        ]);

        Branch::create([
            'store_id' => $request->store_id,
            'branch_name' => $request->branch_name,
            'branch_code' => $request->branch_code,
            'contact_name' => $request->branch_contact_name,
            'contact_phone' => $request->branch_contact_phone,
            'contact_email' => $request->branch_contact_email,
        ]);

        return redirect()->back()->with('success', 'Branch created successfully!');
    }
}
