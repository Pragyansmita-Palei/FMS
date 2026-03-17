@extends('layouts.app')
@section('title', 'Interiors | FurnishPro')

@section('content')

<div class="container-fluid">

<div class="card border-0 shadow-sm rounded-4">

<div class="d-flex justify-content-between align-items-center px-4 py-3">
    <h5 class="mb-0 fw-semibold">Interiors</h5>

    {{-- <button class="btn btn-green"
            data-bs-toggle="modal"
            data-bs-target="#addInteriorModal">
        + Add Interior
    </button> --}}
</div>

<div class="border-top"></div>

<div class="px-4 py-3">

<div class="border rounded-3 overflow-hidden">

<div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="fw-semibold">Interior Details</span>

    <button class="btn btn-green"
            data-bs-toggle="modal"
            data-bs-target="#addInteriorModal">
        + Add Interior
    </button>

</div>

<div class="border-top"></div>

@if(session('success'))
<div class="px-3 py-2">
    <div class="alert alert-success mb-0">
        {{ session('success') }}
    </div>
</div>
<div class="border-top"></div>
@endif

<div class="px-3 py-2">
    <form method="GET" action="{{ route('interiors.index') }}">
        <div class="row">
            <div class="col-md-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control form-control-sm"
                       placeholder="Search firm name, phone or email">
            </div>
        </div>
    </form>
</div>

<div class="border-top"></div>

<div class="table-responsive">
<table class="table table-hover align-middle mb-0">

<thead class="table-light">
<tr>
    <th class="ps-3">#</th>
    <th>Firm Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Address</th>
    <th class="text-end pe-3">Actions</th>
</tr>
</thead>

<tbody>

@forelse($interiors as $key => $interior)

<tr>
    <td class="ps-3">
        {{ $interiors->firstItem() + $key }}
    </td>

    <td class="fw-semibold">{{ $interior->firm_name }}</td>
    <td>{{ $interior->email ?? '-' }}</td>

    <td>
        @if($interior->phone)
            +91 {{ $interior->phone }}
        @else
            -
        @endif
    </td>

    <td>{{ $interior->address ?? '-' }}</td>

    <td class="text-end pe-3">

        {{-- View --}}
        <a href="javascript:void(0)"
           class="text-decoration-none text-secondary me-2"
           data-bs-toggle="modal"
           data-bs-target="#viewInteriorModal{{ $interior->id }}">
            <i class="bi bi-eye"></i>
        </a>

        {{-- Edit --}}
        <a href="javascript:void(0)"
           class="text-decoration-none text-secondary me-2"
           data-bs-toggle="modal"
           data-bs-target="#editInteriorModal{{ $interior->id }}">
            <i class="bi bi-pencil-square"></i>
        </a>

        {{-- Delete --}}
        <form method="POST"
              action="{{ route('interiors.destroy', $interior->id) }}"
              class="d-inline delete-form">
            @csrf
            @method('DELETE')

            <button type="button"
                    class="btn p-0 border-0 bg-transparent text-secondary btn-delete">
                <i class="bi bi-trash"></i>
            </button>
        </form>

    </td>
</tr>

{{-- ===================== EDIT MODAL ===================== --}}
<div class="modal fade"
     id="editInteriorModal{{ $interior->id }}"
     tabindex="-1">

<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<form method="POST"
      action="{{ route('interiors.update', $interior->id) }}">

@csrf
@method('PUT')

<div class="modal-header">
    <h5 class="modal-title">Edit Interior</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="row g-3">

<div class="col-md-6">
    <label class="form-label"> Name <span class="text-danger">*</span></label>
    <input type="text"
           name="firm_name"
           class="form-control"
           value="{{ $interior->firm_name }}"
           required>
</div>

<div class="col-md-6">
    <label class="form-label">Phone</label>
    <div class="input-group">
        <span class="input-group-text">+91</span>
        <input type="text"
               name="phone"
               class="form-control"
               value="{{ $interior->phone }}">
    </div>
</div>

<div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email"
           name="email"
           class="form-control"
           value="{{ $interior->email }}">
</div>

<div class="col-md-6">
    <label class="form-label">Address</label>
    <input type="text"
           name="address"
           class="form-control"
           value="{{ $interior->address }}">
</div>

</div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-green">Update Interior</button>
</div>

</form>

</div>
</div>
</div>
{{-- ===================== END EDIT MODAL ===================== --}}

{{-- ===================== VIEW INTERIOR MODAL ===================== --}}
<div class="modal fade"
     id="viewInteriorModal{{ $interior->id }}"
     tabindex="-1">

    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Interior Details – {{ $interior->firm_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Name</div>
                        <div>{{ $interior->firm_name }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Email</div>
                        <div>{{ $interior->email ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Phone</div>
                        <div>
                            {{ $interior->phone ? '+91 '.$interior->phone : '-' }}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="fw-semibold text-muted">Address</div>
                        <div>
                            {{ $interior->address ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
{{-- ===================== END VIEW MODAL ===================== --}}

@empty

<tr>
    <td colspan="6" class="text-center text-muted py-4">
        No interiors found
    </td>
</tr>

@endforelse

</tbody>
</table>
</div>

<div class="border-top d-flex justify-content-end px-3 py-2">
    {{ $interiors->links('pagination::bootstrap-5') }}
</div>

</div>
</div>
</div>
</div>

{{-- ===================== ADD MODAL ===================== --}}
<div class="modal fade" id="addInteriorModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<form method="POST" action="{{ route('interiors.store') }}">
@csrf

<div class="modal-header">
    <h5 class="modal-title">Add Interior</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="row g-3">

<div class="col-md-6">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="firm_name" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Phone</label>
    <div class="input-group">
        <span class="input-group-text">+91</span>
        <input type="text" name="phone" class="form-control">
    </div>
</div>

<div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control">
</div>

<div class="col-md-6">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control">
</div>

</div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-green">Save Interior</button>
</div>

</form>

</div>
</div>
</div>
{{-- ===================== END ADD MODAL ===================== --}}

@endsection
