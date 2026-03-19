<?php

namespace App\Http\Controllers;

use App\Models\Labour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LabourController extends Controller
{
    // ================= LIST =================
    public function index(Request $request)
    {
        $query = Labour::with('user');

        if ($request->filled('search')) {
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

        $labours = $query->latest()->paginate(10)->withQueryString();

        return view('labours.index', compact('labours'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('labours.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|digits_between:10,15|unique:users,phone|unique:labours,phone',
            'email'     => 'nullable|email|max:255|unique:users,email|unique:labours,email',
            'address'   => 'required|string',
            'rate_type' => 'required|in:day,hour',
            'price'     => 'required|numeric|min:0',
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
                if (\Spatie\Permission\Models\Role::where('name', 'labour')->exists()) {
                    $user->assignRole('labour');
                }
            }

            $user->labour()->create([
                'name'      => $request->name,
                'phone'     => $request->phone ?: null,
                'email'     => $request->email ?: null,
                'address'   => $request->address,
                'rate_type' => $request->rate_type,
                'price'     => $request->price,
            ]);
        });

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour added successfully');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $labour = Labour::findOrFail($id);

        return view('labours.edit', compact('labour'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $labour = Labour::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|digits_between:10,15|unique:users,phone,' . $labour->user_id . '|unique:labours,phone,' . $labour->id,
            'email'     => 'nullable|email|max:255|unique:users,email,' . $labour->user_id . '|unique:labours,email,' . $labour->id,
            'address'   => 'required|string',
            'rate_type' => 'required|in:day,hour',
            'price'     => 'required|numeric|min:0',
        ], [
            'email.unique' => 'This email already exists.',
            'phone.unique' => 'This phone already exists.',
        ]);

        DB::transaction(function () use ($request, $labour) {
            $user = $labour->user;

            if ($user) {
                $user->update([
                    'name'  => $request->name,
                    'email' => $request->email ?: null,
                    'phone' => $request->phone ?: null,
                ]);
            }

            $labour->update([
                'name'      => $request->name,
                'phone'     => $request->phone ?: null,
                'email'     => $request->email ?: null,
                'address'   => $request->address,
                'rate_type' => $request->rate_type,
                'price'     => $request->price,
            ]);
        });

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour updated successfully');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        $labour = Labour::findOrFail($id);

        DB::transaction(function () use ($labour) {
            if ($labour->user) {
                $labour->user->delete();
            }

            $labour->delete();
        });

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour deleted successfully');
    }
}
