<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesAssociate;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // List users
  

public function index(Request $request)
{
    $users = User::with('roles')
        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('users.index', compact('users'));
}

    // Show create form
   public function create()
{
    $roles = Role::all();
    $permissions = Permission::all();

    return view('users.create', compact('roles','permissions'));
}

    // Store user

public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'roles'    => 'required',
        'user_permissions' => 'nullable|array'
    ]);

    DB::transaction(function () use ($request) {

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // assign roles
        $user->assignRole($request->roles);

        // ✅ assign user specific permissions
        $user->syncPermissions($request->user_permissions ?? []);

        $roles = (array) $request->roles;

        // auto create sales associate when role selected
        if (in_array('sales_associates', $roles)) {
            $user->salesAssociate()->create([]);
        }
    });

    return redirect()
        ->route('users.index')
        ->with('success', 'User created successfully');
}
    // Show edit form
   public function edit($id)
{
    $user = User::findOrFail($id);
    $roles = Role::all();
    $userRoles = $user->roles->pluck('name')->toArray();

    $permissions = Permission::all();
    $userPermissions = $user->permissions->pluck('name')->toArray();

    return view('users.edit', compact(
        'user',
        'roles',
        'userRoles',
        'permissions',
        'userPermissions'
    ));
}

    // Update user
   public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'roles' => 'required'
    ]);

    $user->update([
        'name'  => $request->name,
        'email' => $request->email,
    ]);

    if ($request->password) {
        $user->update([
            'password' => Hash::make($request->password)
        ]);
    }

    // roles (for all users with that role)
    $user->syncRoles($request->roles);

    // ✅ user specific permissions (only this user)
    $user->syncPermissions($request->user_permissions ?? []);

    return redirect()->route('users.index')
        ->with('success', 'User updated successfully');
}

    // Delete user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully');
    }

     // Show logged-in user's profile
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    // Update logged-in user's profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }

}
