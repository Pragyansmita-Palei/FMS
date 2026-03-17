@extends('layouts.app')
@section('title', 'Selling Units | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Selling Units</h5>

            {{-- <button type="button"
                    class="btn btn-green"
                    data-bs-toggle="modal"
                    data-bs-target="#addUnitModal">
                + Add Selling Unit
            </button> --}}
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Details Header --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Selling Unit Details</span>
                     <button type="button"
                    class="btn btn-green"
                    data-bs-toggle="modal"
                    data-bs-target="#addUnitModal">
                + Add Selling Unit
            </button>
                </div>

                <div class="border-top"></div>

                {{-- Search Row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('selling-units.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search selling unit">
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
                                <th>Group Type</th>
                                <th>Selling Unit</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($units as $unit)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">
                                        {{ $unit->groupType->name ?? '-' }}
                                    </td>
                                    <td>{{ $unit->unit_name }}</td>

                                    <td class="text-end pe-3">

                                        <!-- Edit -->
                                        <a href="javascript:void(0)"
                                           class="text-decoration-none text-secondary me-2"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editUnitModal{{ $unit->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST"
                                              action="{{ route('selling-units.destroy', $unit->id) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-secondary"
                                                    onclick="return confirm('Delete this selling unit?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- ================= EDIT MODAL ================= --}}
                                <div class="modal fade"
                                     id="editUnitModal{{ $unit->id }}"
                                     tabindex="-1">

                                    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Edit Selling Unit
                                                </h5>
                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <form method="POST"
                                                      action="{{ route('selling-units.update', $unit->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="row g-3">

                                                        <div class="col-md-12">
                                                            <label class="form-label">
                                                                Group Type <span class="text-danger">*</span>
                                                            </label>
                                                            <select name="group_type_id"
                                                                    class="form-control select2"
                                                                    required>
                                                                @foreach($groupTypes as $g)
                                                                    <option value="{{ $g->id }}"
                                                                        {{ $unit->group_type_id == $g->id ? 'selected' : '' }}>
                                                                        {{ $g->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="form-label">
                                                                Selling Unit <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text"
                                                                   name="unit_name"
                                                                   value="{{ old('unit_name', $unit->unit_name) }}"
                                                                   class="form-control"
                                                                   required>
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
                                                            Update Selling Unit
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
                                        No selling units found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(method_exists($units, 'links'))
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $units->links('pagination::bootstrap-5') }}
                </div>
                @endif

            </div>

        </div>

    </div>

</div>


{{-- ================= ADD MODAL ================= --}}
<div class="modal fade" id="addUnitModal" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Selling Unit</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('selling-units.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">
                                Group Type <span class="text-danger">*</span>
                            </label>

                            <select name="group_type_id"
                                    class="form-control select2"
                                    required>
                                <option value="">Select Group Type</option>
                                @foreach($groupTypes as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">
                                Selling Unit <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="unit_name"
                                   value="{{ old('unit_name') }}"
                                   class="form-control"
                                   required>
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
                            Save Selling Unit
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
