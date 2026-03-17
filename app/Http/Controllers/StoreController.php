<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Exports\StoresExport;
use App\Exports\StoreImportSampleExport;
use App\Imports\StoresRequiredImport;
use Maatwebsite\Excel\Facades\Excel;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::query();

        if ($request->filled('search')) {
            $query->where('storename', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('store_code', 'like', '%' . $request->search . '%');
        }

        $stores = $query->latest()->paginate(10)->withQueryString();

        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

 public function store(Request $r)
{
    $r->validate([
        'storename' => 'required',
        'phone' => 'required|digits_between:10,15|unique:stores,phone',
        'email' => 'nullable|email|unique:stores,email',
        'address_line1' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pincode' => 'required|digits:6',
        'contact_name'  => 'required|unique:stores,contact_name',
        'contact_phone' => 'required|digits_between:10,15|unique:stores,contact_phone',
        'contact_email' => 'required|email|unique:stores,contact_email',
        // Optional: validate branch arrays
        'branch_name.*' => 'required|string|max:255',
        'branch_contact_name.*' => 'required|string|max:255',
        'branch_contact_phone.*' => 'required|string|max:20',
        'branch_contact_email.*' => 'nullable|email',
    ]);

    // Create Store
    $store = Store::create([
        'store_code' => $this->generateStoreCode(),
        'storename' => $r->storename,
        'phone' => $r->phone,
        'alt_phone' => $r->alt_phone,
        'email' => $r->email,
        'alt_email' => $r->alt_email,
        'address_line1' => $r->address_line1,
        'address_line2' => $r->address_line2,
        'city' => $r->city,
        'state' => $r->state,
        'pincode' => $r->pincode,
        'landmark' => $r->landmark,
        'contact_name' => $r->contact_name,
        'contact_phone' => $r->contact_phone,
        'contact_whatsapp' => $r->contact_whatsapp,
        'contact_email' => $r->contact_email,
        'contact_address' => $r->contact_address,
    ]);

    // Save Branches
    if ($r->has('branch_name')) {
        $branches = [];
        foreach ($r->branch_name as $index => $branchName) {
            $branches[] = [
                'store_id' => $store->id,
                'branch_name' => $branchName,
                'branch_code' => $r->branch_code[$index] ?? null,
                'contact_name' => $r->branch_contact_name[$index],
                'contact_phone' => $r->branch_contact_phone[$index],
                'contact_email' => $r->branch_contact_email[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        \App\Models\Branch::insert($branches);
    }

    return redirect()->route('stores.index')
        ->with('success', 'Store added successfully with branches');
}

    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

  public function update(Request $r, Store $store)
{


    // Validate store details
    $r->validate([
        'storename' => 'required',
        'phone' => 'required|digits_between:10,15|unique:stores,phone,' . $store->id,
        'email' => 'nullable|email|unique:stores,email,' . $store->id,
        'address_line1' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pincode' => 'required|digits:6',
        'contact_name'  => 'required|unique:stores,contact_name,' . $store->id,
        'contact_phone' => 'required|digits_between:10,15|unique:stores,contact_phone,' . $store->id,
        'contact_email' => 'required|email|unique:stores,contact_email,' . $store->id,

        // Branch validation
        'branch_name.*' => 'required|string|max:255',
        'branch_contact_name.*' => 'required|string|max:255',
        'branch_contact_phone.*' => 'required|string|max:20',
        'branch_contact_email.*' => 'nullable|email',
    ]);

    // Update store details
    $store->update([
        'storename' => $r->storename,
        'phone' => $r->phone,
        'alt_phone' => $r->alt_phone,
        'email' => $r->email,
        'alt_email' => $r->alt_email,
        'address_line1' => $r->address_line1,
        'address_line2' => $r->address_line2,
        'city' => $r->city,
        'state' => $r->state,
        'pincode' => $r->pincode,
        'landmark' => $r->landmark,
        'contact_name' => $r->contact_name,
        'contact_phone' => $r->contact_phone,
        'contact_whatsapp' => $r->contact_whatsapp,
        'contact_email' => $r->contact_email,
        'contact_address' => $r->contact_address,
    ]);

    // Update branches
    // First, delete existing branches
   $store->branches()->delete();

if ($r->filled('branch_name')) {

    $branches = [];

    foreach ($r->branch_name as $index => $branchName) {

        if (!$branchName) continue;

        $branches[] = [
            'store_id' => $store->id,
            'branch_name' => $branchName,
            'branch_code' => $r->branch_code[$index] ?? null,
            'contact_name' => $r->branch_contact_name[$index] ?? null,
            'contact_phone' => $r->branch_contact_phone[$index] ?? null,
            'contact_email' => $r->branch_contact_email[$index] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    \App\Models\Branch::insert($branches);
}
    return redirect()->route('stores.index')
        ->with('success', 'Store and branches updated successfully');
}

    public function destroy(Store $store)
    {
        $store->delete();
        return back()->with('success', 'Store deleted');
    }

    private function generateStoreCode()
    {
        $lastStore = Store::latest('id')->first();

        if (!$lastStore) {
            return 'FMS-S-001';
        }

        $lastNumber = (int) substr($lastStore->store_code, -3);
        $newNumber = $lastNumber + 1;

        return 'FMS-S-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    //export for store
    public function export()
{
    return Excel::download(new StoresExport, 'stores.xlsx');
}

public function downloadImportSample()
{
    return Excel::download(
        new StoreImportSampleExport,
        'store_import_sample.xlsx'
    );
}

public function importRequired(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new StoresRequiredImport, $request->file('file'));

    return redirect()
            ->route('stores.index')
            ->with('success','Stores imported successfully.');
}

}
