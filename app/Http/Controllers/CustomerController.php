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
use Illuminate\Validation\Rule;
class CustomerController extends Controller
{
    /* ===================== LIST ===================== */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->withCount('projects')->latest()->paginate(10);

        $last = Customer::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        $customerCode = 'FMS-C-' . $nextId;

        return view('customers.index', compact('customers', 'customerCode'));
    }

    /* ===================== CREATE ===================== */
    public function create()
    {
        $last = Customer::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;

        $customerCode = 'FMS-C-' . $nextId;

        return view('customers.create', compact('customerCode'));
    }

    /* ===================== STORE ===================== */
    public function store(Request $request)
    {
        $last = Customer::latest('id')->first();
        $nextId = $last ? $last->id + 1 : 1;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',

            'phone' => 'required|digits_between:10,15|unique:users,phone',
            'alternate_phone' => 'nullable|digits_between:10,15|unique:users,alternate_phone',

            'email' => 'required|email|unique:users,email',

            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route('customers.index')
                ->withErrors($validator)
                ->withInput();
        }

        /* ✅ CREATE USER */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('customer');

        /* ✅ CREATE CUSTOMER */
        Customer::create([
            'user_id'         => $user->id,
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

        return redirect()->route('customers.index')
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
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',

        'phone' => [
            'required',
            'digits_between:10,15',
            Rule::unique('users', 'phone')->ignore($customer->user_id),
        ],

        'alternate_phone' => [
            'nullable',
            'digits_between:10,15',
            Rule::unique('users', 'alternate_phone')->ignore($customer->user_id),
        ],

        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($customer->user_id),
        ],

        'address_line1' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pin' => 'required|digits:6',
    ]);

    if ($validator->fails()) {
        return redirect()->route('customers.index')
            ->withErrors($validator)
            ->withInput()
            ->with('edit_customer_id', $customer->id);
    }

    if (
        $request->filled('alternate_phone') &&
        $request->phone == $request->alternate_phone
    ) {
        return redirect()->route('customers.index')
            ->withErrors(['alternate_phone' => 'Alternate phone cannot be the same as phone number.'])
            ->withInput()
            ->with('edit_customer_id', $customer->id);
    }

    /* UPDATE USER */
    $customer->user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'alternate_phone' => $request->alternate_phone,
    ]);

    /* UPDATE CUSTOMER */
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

    return redirect()->route('customers.index')
        ->with('success', 'Customer updated successfully');
}

    /* ===================== DELETE ===================== */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }

    /* ===================== EXPORT ===================== */
    public function exportExcel(Request $request)
    {
        return Excel::download(new CustomersExport($request->search), 'customers.xlsx');
    }

    public function exportCsv(Request $request)
    {
        return Excel::download(new CustomersExport($request->search), 'customers.csv');
    }

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
