@extends('layouts.app')
@section('title', 'Roles | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Roles</h5>

            <div class="d-flex gap-2">
                <!-- <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-primary">
                    Permissions
                </a>

                <a href="{{ route('users.index') }}" class="btn btn-sm btn-warning text-white">
                    Users
                </a> -->

                {{-- <a href="{{ route('admin.roles.create') }}" class="btn btn-green">
                    + Add Role
                </a> --}}
            </div>
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Roles details header row --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Role Details</span>
                     <a href="{{ route('admin.roles.create') }}" class="btn btn-green">
                    + Add Role
                </a>
                </div>

                <div class="border-top"></div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($roles as $role)
                            <tr>

                                <td class="ps-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $role->name }}
                                </td>

                               <td
    title="{{ $role->permissions->pluck('name')->implode(', ') }}"
>
    @if($role->permissions->count())
        {{ $role->permissions->pluck('name')->take(2)->implode(', ') }}
        @if($role->permissions->count() > 2)
            ...
        @endif
    @else
        <span class="text-muted">No permissions</span>
    @endif
</td>

                                <td class="text-end pe-3">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                       class="text-decoration-none text-secondary me-2"
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                          method="POST"
                                          class="d-inline">
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
                                <td colspan="4" class="text-center text-muted py-4">
                                    No roles found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (if paginated) --}}
                @if(method_exists($roles, 'links'))
                    <div class="border-top d-flex justify-content-end px-3 py-2">
                        {{ $roles->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection
