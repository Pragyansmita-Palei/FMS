@extends('layouts.app')
@section('title', 'Labours | FurnishPro')

@section('content')

<div class="container-fluid">

<div class="card border-0 shadow-sm rounded-4">

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center px-4 py-3">
    <h5 class="mb-0 fw-semibold">Labours</h5>
{{--
    <button class="btn btn-green"
            data-bs-toggle="modal"
            data-bs-target="#addLabourModal">
        + Add Labour
    </button> --}}
</div>

<div class="border-top"></div>

<div class="px-4 py-3">

<div class="border rounded-3 overflow-hidden">

<div class="d-flex justify-content-between align-items-center px-3 py-2">
    <span class="fw-semibold">Labour Details</span>

    <button class="btn btn-green"
            data-bs-toggle="modal"
            data-bs-target="#addLabourModal">
        + Add Labour
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

{{-- Search --}}
<div class="px-3 py-2">
    <form method="GET" action="{{ route('labours.index') }}">
        <div class="row">
            <div class="col-md-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control form-control-sm"
                       placeholder="Search labour name, phone or email">
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
    <th>Name</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Rate Type</th>
    <th>Piece</th>
    <th class="text-end pe-3">Actions</th>
</tr>
</thead>

<tbody>

@forelse($labours as $key => $labour)

<tr>
    <td class="ps-3">
        {{ $labours->firstItem() + $key }}
    </td>

    <td class="fw-semibold">{{ $labour->labour_name }}</td>
    <td>{{ $labour->phone_number }}</td>
    <td>{{ $labour->address ?? '-' }}</td>
    <td>{{ $labour->rate_type ?? '-' }}</td>
    <td>{{ $labour->price ?? '-' }}</td>

    <td class="text-end pe-3">

        {{-- View --}}
        <a href="javascript:void(0)"
           class="text-decoration-none text-secondary me-2"
           data-bs-toggle="modal"
           data-bs-target="#viewLabourModal{{ $labour->id }}">
            <i class="bi bi-eye"></i>
        </a>

        {{-- Edit --}}
        <a href="javascript:void(0)"
           class="text-decoration-none text-secondary me-2"
           data-bs-toggle="modal"
           data-bs-target="#editLabourModal{{ $labour->id }}">
            <i class="bi bi-pencil-square"></i>
        </a>

        {{-- Delete --}}
        <form method="POST"
              action="{{ route('labours.destroy', $labour->id) }}"
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
     id="editLabourModal{{ $labour->id }}"
     tabindex="-1">

<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<form method="POST"
      action="{{ route('labours.update', $labour->id) }}">
@csrf
@method('PUT')

<div class="modal-header">
    <h5 class="modal-title">Edit Labour</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="row g-3">

<div class="col-md-6">
    <label class="form-label">Labour Name <span class="text-danger">*</span></label>
    <input type="text"
           name="labour_name"
           class="form-control"
           value="{{ $labour->labour_name }}"
           required>
</div>

<div class="col-md-6">
    <label class="form-label">Phone <span class="text-danger">*</span></label>
    <input type="text"
           name="phone_number"
           class="form-control"
           value="{{ $labour->phone_number }}"
           required>
</div>

<div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email"
           name="email"
           class="form-control"
           value="{{ $labour->email }}">
</div>

<div class="col-md-6">
    <label class="form-label">Rate Type <span class="text-danger">*</span></label>
    <select name="rate_type" class="form-select" required>
        <option value="day" {{ $labour->rate_type == 'day' ? 'selected' : '' }}>Day</option>
        <option value="hour" {{ $labour->rate_type == 'hour' ? 'selected' : '' }}>Hour</option>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Price / Piece <span class="text-danger">*</span></label>
    <input type="number"
           step="0.01"
           name="price"
           class="form-control"
           value="{{ $labour->price }}"
           required>
</div>

<div class="col-md-6">
    <label class="form-label">Address <span class="text-danger">*</span></label>
    <textarea name="address"
              class="form-control"
              rows="2"
              required>{{ $labour->address }}</textarea>
</div>

</div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-green">Update Labour</button>
</div>

</form>

</div>
</div>
</div>
{{-- ===================== END EDIT MODAL ===================== --}}

{{-- ===================== VIEW MODAL ===================== --}}
<div class="modal fade"
     id="viewLabourModal{{ $labour->id }}"
     tabindex="-1">

<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">
        Labour Details – {{ $labour->labour_name }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="row g-3">

<div class="col-md-6">
    <label class="form-label">Labour Name</label>
    <input class="form-control" value="{{ $labour->labour_name }}" readonly>
</div>

<div class="col-md-6">
    <label class="form-label">Phone</label>
    <input class="form-control" value="{{ $labour->phone_number }}" readonly>
</div>

<div class="col-md-6">
    <label class="form-label">Email</label>
    <input class="form-control" value="{{ $labour->email ?? '-' }}" readonly>
</div>

<div class="col-md-6">
    <label class="form-label">Rate Type</label>
    <input class="form-control" value="{{ $labour->rate_type ?? '-' }}" readonly>
</div>

<div class="col-md-6">
    <label class="form-label">Price / Piece</label>
    <input class="form-control" value="{{ $labour->price ?? '-' }}" readonly>
</div>

<div class="col-md-6">
    <label class="form-label">Address</label>
    <textarea class="form-control" rows="2" readonly>{{ $labour->address ?? '-' }}</textarea>
</div>

</div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
</div>

</div>
</div>
</div>
{{-- ===================== END VIEW MODAL ===================== --}}

@empty

<tr>
    <td colspan="7" class="text-center text-muted py-4">
        No labours found
    </td>
</tr>

@endforelse

</tbody>
</table>
</div>

<div class="border-top d-flex justify-content-end px-3 py-2">
    {{ $labours->links('pagination::bootstrap-5') }}
</div>

</div>
</div>
</div>
</div>

{{-- ===================== ADD MODAL ===================== --}}
<div class="modal fade" id="addLabourModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<form method="POST" action="{{ route('labours.store') }}">
@csrf

<div class="modal-header">
    <h5 class="modal-title">Add Labour</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<div class="row g-3">

<div class="col-md-6">
    <label class="form-label">Labour Name <span class="text-danger">*</span></label>
    <input type="text" name="labour_name" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Phone <span class="text-danger">*</span></label>
    <input type="text" name="phone_number" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control">
</div>

<div class="col-md-6">
    <label class="form-label">Rate Type <span class="text-danger">*</span></label>
    <select name="rate_type" class="form-select" required>
        <option value="">Select</option>
        <option value="day">Day</option>
        <option value="hour">Hour</option>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Price / Piece <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="price" class="form-control" required>
</div>

<div class="col-md-6">
    <label class="form-label">Address <span class="text-danger">*</span></label>
    <textarea name="address" class="form-control" rows="2" required></textarea>
</div>

</div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-green">Save Labour</button>
</div>

</form>

</div>
</div>
</div>
{{-- ===================== END ADD MODAL ===================== --}}

@endsection
