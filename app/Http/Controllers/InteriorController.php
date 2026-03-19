<?php

namespace App\Http\Controllers;

use App\Models\Interior;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InteriorController extends Controller
{
    // ================= LIST =================
    public function index(Request $request)
    {
        $query = Interior::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%')
                         ->orWhere('phone', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $interiors = $query->latest()->paginate(10);

        return view('interiors.index', compact('interiors'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email|unique:interiors,email',
            'phone' => 'nullable|digits_between:10,15|unique:users,phone|unique:interiors,phone',
            'address' => 'nullable|string',
        ], [
            'email.unique' => 'This email already exists.',
            'phone.unique' => 'This phone already exists.',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email ?: null,
                'phone'    => $request->phone ?: null,
                'password' => Hash::make('12345678'),
            ]);

            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                if (\Spatie\Permission\Models\Role::where('name', 'interior')->exists()) {
                    $user->assignRole('interior');
                }
            }

            $user->interior()->create([
                'name'    => $request->name,
                'email'   => $request->email ?: null,
                'phone'   => $request->phone ?: null,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('interiors.index')
                         ->with('success', 'Interior added successfully.');
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $interior = Interior::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $interior->user_id .
                       '|unique:interiors,email,' . $interior->id,
            'phone' => 'nullable|digits_between:10,15|unique:users,phone,' . $interior->user_id .
                       '|unique:interiors,phone,' . $interior->id,
            'address' => 'nullable|string',
        ], [
            'email.unique' => 'This email already exists.',
            'phone.unique' => 'This phone already exists.',
        ]);

        DB::transaction(function () use ($request, $interior) {

            if ($interior->user) {
                $interior->user->update([
                    'name'  => $request->name,
                    'email' => $request->email ?: null,
                    'phone' => $request->phone ?: null,
                ]);
            }

            $interior->update([
                'name'    => $request->name,
                'email'   => $request->email ?: null,
                'phone'   => $request->phone ?: null,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('interiors.index')
                         ->with('success', 'Interior updated successfully.');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $interior = Interior::findOrFail($id);

        DB::transaction(function () use ($interior) {

            if ($interior->user) {
                $interior->user->delete();
            }

            $interior->delete();
        });

        return redirect()->route('interiors.index')
                         ->with('success', 'Interior deleted successfully.');
    }
}
