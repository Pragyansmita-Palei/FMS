<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Tailor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Exports\TailorsExport;
use App\Imports\TailorsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TailorsSampleExport;

class TailorController extends Controller
{
   public function index()
{
    $tailors = Tailor::with('user')->latest()->paginate(10);

    $lastTailor = Tailor::latest()->first();
    $lastTailorId = $lastTailor
        ? intval(str_replace('FMS-T-', '', $lastTailor->tailor_id))
        : 0;

    return view('tailors.index', compact('tailors','lastTailorId'));
}
    public function create()
    {
        // Get last tailor ID for auto-generation
        $lastTailor = Tailor::latest()->first();
        $lastTailorId = $lastTailor ? intval(str_replace('FMS-T-', '', $lastTailor->tailor_id)) : 0;

        return view('tailors.create', compact('lastTailorId'));
    }
public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6',

        'phone' => 'required|digits_between:10,15|unique:tailors,phone',
        'alternate_phone' => 'nullable|digits_between:10,15',
        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pin' => 'required|digits:6',
        'landmark' => 'nullable|string|max:255',
    ]);

    DB::transaction(function () use ($request) {

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('tailors');

        $lastTailor = Tailor::latest()->first();

        $tailorId = 'FMS-T-' . (
            $lastTailor
                ? intval(str_replace('FMS-T-', '', $lastTailor->tailor_id)) + 1
                : 1
        );

        Tailor::create([
            'user_id'         => $user->id,
            'tailor_id'       => $tailorId,
            'phone'           => $request->phone,
            'alternate_phone' => $request->alternate_phone,   // ✅ FIXED
            'address_line1'   => $request->address_line1,
            'address_line2'   => $request->address_line2,
            'city'            => $request->city,
            'state'           => $request->state,
            'pin'             => $request->pin,
            'landmark'        => $request->landmark,
        ]);
    });

    return redirect()->route('tailors.index')
        ->with('success', 'Tailor added successfully.');
}



public function update(Request $request, Tailor $tailor)
{
    $request->validate([
        // user table
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $tailor->user_id,

        // tailor table
        'phone' => 'required|string|max:20|unique:tailors,phone,' . $tailor->id,
        'alternate_phone' => 'nullable|string|max:20',
        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pin' => 'required|string|max:10',
        'landmark' => 'nullable|string|max:255',

        // password
        'password' => 'nullable|min:6',
    ]);

    /*
     |--------------------------------------
     | Update USER table
     |--------------------------------------
     */
    $user = $tailor->user;

    $user->name  = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    /*
     |--------------------------------------
     | Update TAILOR table
     |--------------------------------------
     */
    $tailor->update([
        'phone'            => $request->phone,
        'alternate_phone'  => $request->alternate_phone,
        'address_line1'    => $request->address_line1,
        'address_line2'    => $request->address_line2,
        'city'             => $request->city,
        'state'            => $request->state,
        'pin'              => $request->pin,
        'landmark'         => $request->landmark,
    ]);

    return redirect()
        ->route('tailors.index')
        ->with('success', 'Tailor updated successfully.');
}



    public function show(Tailor $tailor)
    {
        return view('tailors.show', compact('tailor'));
    }

    public function edit(Tailor $tailor)
    {
        return view('tailors.edit', compact('tailor'));
    }


    public function destroy(Tailor $tailor)
    {
        $tailor->delete();
        return redirect()->route('tailors.index')->with('success', 'Tailor deleted successfully.');
    }


    public function exportExcel()
{
    return Excel::download(new TailorsExport(), 'tailors.xlsx');
}

public function exportCsv()
{
    return Excel::download(
        new TailorsExport(),
        'tailors.csv',
        \Maatwebsite\Excel\Excel::CSV
    );
}

public function exportPdf()
{
    $tailors = Tailor::with('user')->latest()->get();

    $pdf = Pdf::loadView('pdf.tailors_pdf', compact('tailors'));

    return $pdf->download('tailors.pdf');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new TailorsImport(), $request->file('file'));

    return redirect()
        ->route('tailors.index')
        ->with('success', 'Tailors imported successfully.');
}

public function downloadSample()
{
    return Excel::download(
        new TailorsSampleExport(),
        'tailors_import_sample.xlsx'
    );
}

}
