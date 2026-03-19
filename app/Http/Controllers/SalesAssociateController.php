<?php

namespace App\Http\Controllers;

use App\Models\SalesAssociate;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesAssociatesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesAssociateController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesAssociate::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('alternate_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('alternate_phone', 'like', "%{$search}%");
                  });
            });
        }

        $salesAssociates = $query->latest()->paginate(10);

        $lastSA = SalesAssociate::latest('id')->first();

        $lastSalesId = $lastSA
            ? (int) str_replace('FMS-SA-', '', $lastSA->sales_id)
            : 0;

        return view('sales.index', compact('salesAssociates', 'lastSalesId'));
    }

    public function create()
    {
        $lastSA = SalesAssociate::latest()->first();
        $lastSalesId = $lastSA ? intval(str_replace('FMS-SA-', '', $lastSA->sales_id)) : 0;

        return view('sales.create', compact('lastSalesId'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone'    => 'required|digits_between:10,15|unique:users,phone|unique:sales_associates,phone',
        'alternate_phone' => 'nullable|digits_between:10,15|different:phone|unique:users,alternate_phone|unique:sales_associates,alternate_phone',

        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city'  => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pin'   => 'required|digits:6',
        'landmark' => 'nullable|string|max:255',
    ], [
        'email.unique' => 'This email already exists.',
        'phone.unique' => 'This phone already exists.',
        'alternate_phone.unique' => 'This alternate phone already exists.',
        'alternate_phone.different' => 'Alternate phone must be different from phone.',
    ]);

    DB::transaction(function () use ($request) {
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'phone'           => $request->phone,
            'alternate_phone' => $request->alternate_phone,
        ]);

        $user->assignRole('sales_associates');

        $user->salesAssociate()->create([
            'phone'           => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'address_line1'   => $request->address_line1,
            'address_line2'   => $request->address_line2,
            'city'            => $request->city,
            'state'           => $request->state,
            'pin'             => $request->pin,
            'landmark'        => $request->landmark,
        ]);
    });

    return redirect()
        ->route('sales_associates.index')
        ->with('success', 'Sales Associate added successfully.');
}

    public function edit(SalesAssociate $sales_associate)
    {
        return view('sales.edit', compact('sales_associate'));
    }

    public function update(Request $request, SalesAssociate $sales_associate)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $sales_associate->user_id,
        'phone' => 'required|digits_between:10,15|unique:users,phone,' . $sales_associate->user_id . '|unique:sales_associates,phone,' . $sales_associate->id,
        'alternate_phone' => 'nullable|digits_between:10,15|different:phone|unique:users,alternate_phone,' . $sales_associate->user_id . '|unique:sales_associates,alternate_phone,' . $sales_associate->id,

        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pin' => 'required|digits:6',
        'landmark' => 'nullable|string|max:255',
    ], [
        'email.unique' => 'This email already exists.',
        'phone.unique' => 'This phone already exists.',
        'alternate_phone.unique' => 'This alternate phone already exists.',
        'alternate_phone.different' => 'Alternate phone must be different from phone.',
    ]);

    DB::transaction(function () use ($request, $sales_associate) {
        $user = $sales_associate->user;

        $user->update([
            'name'            => $request->name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'alternate_phone' => $request->alternate_phone,
        ]);

        $sales_associate->update([
            'phone'           => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'address_line1'   => $request->address_line1,
            'address_line2'   => $request->address_line2,
            'city'            => $request->city,
            'state'           => $request->state,
            'pin'             => $request->pin,
            'landmark'        => $request->landmark,
        ]);
    });

    return redirect()
        ->route('sales_associates.index')
        ->with('success', 'Sales Associate updated successfully.');
}

    public function destroy(SalesAssociate $sales_associate)
    {
        $sales_associate->delete();
        return redirect()->route('sales_associates.index')->with('success', 'Sales Associate deleted successfully.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new SalesAssociatesExport($request),
            'sales_associates.xlsx'
        );
    }

    public function exportCsv(Request $request)
    {
        return Excel::download(
            new SalesAssociatesExport($request),
            'sales_associates.csv'
        );
    }

    public function exportPdf(Request $request)
    {
        $query = SalesAssociate::with('user');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('alternate_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('alternate_phone', 'like', "%{$search}%");
                  });
            });
        }

        $salesAssociates = $query->latest()->get();

        $pdf = Pdf::loadView('pdf.sales_pdf', compact('salesAssociates'));

        return $pdf->download('sales_associates.pdf');
    }
}
