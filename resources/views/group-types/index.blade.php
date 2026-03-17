@extends('layouts.app')
@section('title', 'Product Group | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Product Group</h5>

            {{-- <button type="button"
                    class="btn btn-green"
                    data-bs-toggle="modal"
                    data-bs-target="#addGroupModal">
                + Add Product Group
            </button> --}}
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Details Header --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Product Group Details</span>
                     <button type="button"
                    class="btn btn-green"
                    data-bs-toggle="modal"
                    data-bs-target="#addGroupModal">
                + Add Product Group
            </button>
                </div>

                <div class="border-top"></div>

                {{-- Search Row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('group-types.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search product group">
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
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($groups as $group)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $group->name }}</td>
                                    <td>
                                        {{ $group->status ? 'Active' : 'Inactive' }}
                                    </td>

                                    <td class="text-end pe-3">

                                        <!-- Edit -->
                                        <a href="javascript:void(0)"
                                           class="text-decoration-none text-secondary me-2"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editGroupModal{{ $group->id }}"
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST"
                                              action="{{ route('group-types.destroy', $group) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-secondary"
                                                    onclick="return confirm('Delete this product group?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- ================= EDIT MODAL ================= -->
                                <div class="modal fade"
                                     id="editGroupModal{{ $group->id }}"
                                     tabindex="-1">

                                    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Edit Product Group – {{ $group->name }}
                                                </h5>
                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <form method="POST"
                                                      action="{{ route('group-types.update', $group->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="row g-3">

                                                        <div class="col-md-12">
                                                            <label class="form-label">
                                                                Product Group Name
                                                                <span class="text-danger">*</span>
                                                            </label>

                                                            <input type="text"
                                                                   name="name"
                                                                   value="{{ old('name', $group->name) }}"
                                                                   class="form-control"
                                                                   required>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input"
                                                                       type="checkbox"
                                                                       name="status"
                                                                       value="1"
                                                                       {{ $group->status ? 'checked' : '' }}>
                                                                <label class="form-check-label">
                                                                    Active
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="text-end mt-4">
                                                        <button type="button"
                                                                class="btn btn-light"
                                                                data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>

                                                        <button type="submit"
                                                                class="btn btn-green">
                                                            Update Product Group
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="text-center text-muted py-4">
                                        No product group found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $groups->links('pagination::bootstrap-5') }}
                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================= ADD MODAL ================= -->
<div class="modal fade" id="addGroupModal" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Product Group</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('group-types.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">
                                Product Group Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="status"
                                       value="1"
                                       {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Active
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-green">
                            Save Product Group
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
