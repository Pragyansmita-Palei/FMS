@extends('layouts.app')
@section('title', 'Users | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Users</h5>

            <div class="d-flex gap-2">
                <!-- <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-primary">
                    Roles
                </a>

                <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-info text-white">
                    Permissions
                </a> -->

                {{-- <a href="{{ route('users.create') }}" class="btn btn-green">
                    + Add User
                </a> --}}
            </div>
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Users details header row --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">

                    <span class="fw-semibold">User Details</span>
                     <a href="{{ route('users.create') }}" class="btn btn-green">
                    + Add User
                </a>

                </div>

                <div class="border-top"></div>

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('users.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search name or email">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border-top"></div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($users as $user)
                            <tr>

                                <td class="ps-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $user->name }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>
                                    @forelse($user->roles as $role)
                                            {{ $role->name }}
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>

                                <td class="text-end pe-3">

                                    {{-- Edit --}}
                                    <a href="{{ route('users.edit',$user->id) }}"
                                       class="text-decoration-none text-secondary me-2"
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('users.destroy',$user->id) }}"
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Are you sure?')"
                                                class="btn p-0 border-0 bg-transparent text-secondary"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center text-muted py-4">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

              @if ($users->hasPages())
    <div class="border-top d-flex justify-content-end align-items-center gap-4 px-3 py-3">

        <div class="text-muted small mb-3">
            {{ $users->firstItem() }}–{{ $users->lastItem() }}
            of {{ $users->total() }}
        </div>

        <div>
            {{ $users->links() }}
        </div>

    </div>
@endif

            </div>

        </div>

    </div>

</div>

@endsection
