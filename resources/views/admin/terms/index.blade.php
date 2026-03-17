@extends('layouts.app')
@section('title', 'Terms & Conditions | FurnishPro')

@section('content')

<div class="container-fluid">

<div class="card border-0 shadow-sm rounded-4">

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center px-4 py-3">
<h5 class="mb-0 fw-semibold">Terms & Conditions</h5>
</div>

<div class="border-top"></div>

{{-- Main Section --}}
<div class="px-4 py-3">

<div class="border rounded-3 overflow-hidden">

{{-- Header row --}}
<div class="d-flex justify-content-between align-items-center px-3 py-2">
<span class="fw-semibold">Terms List</span>

<button type="button"
class="btn btn-green"
data-bs-toggle="modal"
data-bs-target="#addTermModal">
+ Add Term
</button>

</div>

<div class="border-top"></div>

{{-- Search --}}
<div class="px-3 py-2">

<form method="GET" action="{{ route('admin.terms.index') }}">

<div class="row">
<div class="col-md-4">

<input type="text"
name="search"
value="{{ request('search') }}"
class="form-control form-control-sm"
placeholder="Search title or description">

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
<th>Title</th>
<th>Description</th>
{{-- <th>Status</th> --}}
<th class="text-end pe-3">Actions</th>

</tr>
</thead>

<tbody>

@forelse($terms as $term)

<tr>

<td class="ps-3">{{ $loop->iteration }}</td>

<td class="fw-semibold">{{ $term->title }}</td>

<td title="{{ $term->description }}">
    {{ \Illuminate\Support\Str::before($term->description, "\n") }}
</td>
{{-- <td>
{{ $term->status ? 'Active' : 'Inactive' }}

</td> --}}

<td class="text-end pe-3">

{{-- View --}}
<a href="javascript:void(0)"
class="text-decoration-none text-secondary me-2"
data-bs-toggle="modal"
data-bs-target="#viewTermModal{{ $term->id }}"
title="View">

<i class="bi bi-eye"></i>

</a>

{{-- Edit --}}
<a href="javascript:void(0)"
class="text-decoration-none text-secondary me-2"
data-bs-toggle="modal"
data-bs-target="#editTermModal{{ $term->id }}"
title="Edit">

<i class="bi bi-pencil-square"></i>

</a>

{{-- Delete --}}
<form method="POST"
action="{{ route('admin.terms.destroy',$term->id) }}"
class="d-inline">

@csrf
@method('DELETE')

<button type="submit"
class="btn p-0 border-0 bg-transparent text-secondary"
title="Delete"
onclick="return confirm('Are you sure you want to delete this term?')">

<i class="bi bi-trash"></i>

</button>

</form>

</td>

</tr>

{{-- VIEW MODAL --}}
<div class="modal fade"
id="viewTermModal{{ $term->id }}"
tabindex="-1">

<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">

<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Term Details</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="row gy-3">

<div class="col-md-6">
<div class="fw-semibold text-muted">Title</div>
<div>{{ $term->title }}</div>
</div>

<div class="col-md-6">
<div class="fw-semibold text-muted">Status</div>
<div>{{ $term->status ? 'Active' : 'Inactive' }}</div>
</div>

<div class="col-md-12">
<div class="fw-semibold text-muted">Description</div>
<div>{{ $term->description }}</div>
</div>

</div>

</div>

<div class="modal-footer">
<button class="btn btn-light" data-bs-dismiss="modal">
Close
</button>
</div>

</div>
</div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade"
id="editTermModal{{ $term->id }}"
tabindex="-1">

<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">

<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Edit Term – {{ $term->title }}</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST"
action="{{ route('admin.terms.update',$term->id) }}">

@csrf

<div class="row g-3">

<div class="col-md-12">
<label class="form-label">Title</label>
<input type="text"
name="title"
class="form-control"
value="{{ $term->title }}">
</div>

<div class="col-md-12">
<label class="form-label">Description</label>

<textarea name="description"
class="form-control"
rows="3">{{ $term->description }}</textarea>

</div>

<div class="col-md-12">

{{-- <div class="form-check mt-2">

<input class="form-check-input"
type="checkbox"
name="status"
value="1"
{{ $term->status ? 'checked' : '' }}>

<label class="form-check-label">
Active
</label>

</div> --}}

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
Update Term
</button>

</div>

</form>

</div>
</div>
</div>
</div>

@empty

<tr>
<td colspan="5"
class="text-center text-muted py-4">
No terms found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

@if ($terms->total() > 10)

<div class="d-flex justify-content-end align-items-center gap-4 px-3 py-3">

<div class="text-muted small">
{{ $terms->firstItem() }}–{{ $terms->lastItem() }}
of {{ $terms->total() }}
</div>

<div>
{{ $terms->links() }}
</div>

</div>

@endif

</div>

</div>

</div>

</div>

{{-- ADD TERM MODAL --}}
<div class="modal fade"
id="addTermModal"
tabindex="-1">

<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">

<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Add New Term</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST"
action="{{ route('admin.terms.store') }}">

@csrf

<div class="row g-3">

<div class="col-md-12">
<label class="form-label">
Title
</label>

<input type="text"
name="title"
class="form-control">
</div>

<div class="col-md-12">

<label class="form-label">
Description <span class="text-danger">*</span>
</label>

<textarea name="description"
class="form-control"
rows="3"
required></textarea>

</div>

<div class="col-md-12">

{{-- <div class="form-check mt-2">

<input class="form-check-input"
type="checkbox"
name="status"
value="1"
checked>

<label class="form-check-label">
Active
</label>

</div> --}}

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
Save Term
</button>

</div>

</form>

</div>
</div>
</div>
</div>

@endsection
