@extends('layouts.app')
@section('title', 'Permissions | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Permissions</h5>

            <div class="d-flex gap-2">
                <!-- <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-primary">
                    Roles
                </a>

                <a href="{{ route('users.index') }}" class="btn btn-sm btn-warning text-white">
                    Users
                </a> -->

                {{-- <a href="{{ route('admin.permissions.create') }}" class="btn btn-green">
                    + Add Permission
                </a> --}}
            </div>
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Permissions details header row --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Permission Details</span>
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-green">
                    + Add Permission
                </a>
                </div>

                <div class="border-top"></div>

                {{-- (Optional) Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('admin.permissions.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search permission name">
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
                            <th>Permission Name</th>
                            <th>Created At</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($permissions as $permission)
                            <tr>

                                <td class="ps-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $permission->name }}
                                </td>

                                <td>
                                    {{ $permission->created_at->format('d M, Y') }}
                                </td>

                                <td class="text-end pe-3">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                       class="text-decoration-none text-secondary me-2"
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
                                          method="POST"
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
                                <td colspan="4"
                                    class="text-center text-muted py-4">
                                    No permissions found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

             @if ($permissions->hasPages())
    <div class="border-top d-flex justify-content-end align-items-center gap-4 px-3 py-3">

        <div class="text-muted small mb-3">
            {{ $permissions->firstItem() }}–{{ $permissions->lastItem() }}
            of {{ $permissions->total() }}
        </div>

        <div>
            {{ $permissions->links() }}
        </div>

    </div>
@endif

            </div>

        </div>

    </div>

</div>

@endsection
