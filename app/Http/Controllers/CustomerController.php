<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
   public function index(Request $request)
{
    $query = Customer::query();

    // Search
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    $customers = $query
        ->withCount('projects')
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // ✅ ADD THIS
    $last = Customer::latest('id')->first();
    $nextId = $last ? $last->id + 1 : 1;
    $customerCode = 'FMS-C-' . $nextId;

    // ✅ PASS IT
    return view('customers.index', compact('customers', 'customerCode'));
}
    public function create()
    {
        $last = Customer::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;

        $customerCode = 'FMS-C-' . $nextId;

        return view('customers.create', compact('customerCode'));
    }



public function store(Request $request)
{
    $last = Customer::latest('id')->first();
    $nextId = $last ? $last->id + 1 : 1;

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'phone' => 'required|digits_between:10,15',
        'email' => 'required|email|unique:users,email', // ✅ check users table
        'address_line1' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pin' => 'required|digits:6',
    ]);

   if ($validator->fails()) {
    return redirect()
    ->route('customers.index')
    ->withErrors($validator)
    ->withInput();
}

    // ✅ STEP 1: Create User
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make('12345678'), // default password
    ]);

    // ✅ STEP 2: Assign Role (Spatie or manual)
    $user->assignRole('customer'); // if using spatie

    // ✅ STEP 3: Create Customer
    Customer::create([
        'user_id'         => $user->id, // 🔥 link
        'customer_code'   => 'FMS-C-' . $nextId,
        'name'            => $request->name,
        'phone'           => $request->phone,
        'alternate_phone' => $request->alternate_phone,
        'email'           => $request->email,
        'address_line1'   => $request->address_line1,
        'address_line2'   => $request->address_line2,
        'city'            => $request->city,
        'state'           => $request->state,
        'pin'             => $request->pin,
        'landmark'        => $request->landmark,
    ]);

    return redirect()
        ->route('customers.index')
        ->with('success', 'Customer + User created successfully!');
}

    /* ===================== EDIT ===================== */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /* ===================== UPDATE ===================== */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:customers,name,' . $customer->id,
            'phone' => 'required|string|max:15|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin' => 'required|string|max:10',
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'email' => $request->email,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'pin' => $request->pin,
            'landmark' => $request->landmark,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }


    /* ===================== DELETE ===================== */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }

    public function exportExcel(Request $request)
{
    return Excel::download(
        new CustomersExport($request->search),
        'customers.xlsx'
    );
}

public function exportCsv(Request $request)
{
    return Excel::download(
        new CustomersExport($request->search),
        'customers.csv'
    );
}
//export customer list
public function exportPdf(Request $request)
{
    $query = Customer::withCount('projects');

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    $customers = $query->latest()->get();

    $pdf = Pdf::loadView('pdf.customer_pdf', compact('customers'));

    return $pdf->download('customers.pdf');
}

}
