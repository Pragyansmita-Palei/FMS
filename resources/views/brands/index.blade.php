@extends('layouts.app')
@section('title', 'Brands | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Brands</h5>

          {{-- <button type="button"
        class="btn btn-green"
        data-bs-toggle="modal"
        data-bs-target="#addBrandModal">
    + Add Brand
</button> --}}
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX (Brand details → pagination) --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Brand details header row --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Brand Details</span>
                              <button type="button"
        class="btn btn-green"
        data-bs-toggle="modal"
        data-bs-target="#addBrandModal">
    + Add Brand
</button>
                </div>

                <div class="border-top"></div>

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('brands.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search brand name or description">
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
                                <th>Description</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $brand->name }}</td>
<td title="{{ $brand->description }}">
    {{ \Illuminate\Support\Str::words($brand->description, 2, '...') ?? '-' }}
</td>                                   <td class="text-end pe-3">
                                     <!-- View -->
    <a href="javascript:void(0)"
       class="text-decoration-none text-secondary me-2"
       data-bs-toggle="modal"
       data-bs-target="#viewBrandModal{{ $brand->id }}"
       title="View">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Edit -->
   <a href="javascript:void(0)"
   class="text-decoration-none text-secondary me-2"
   data-bs-toggle="modal"
   data-bs-target="#editBrandModal{{ $brand->id }}"
   title="Edit">
    <i class="bi bi-pencil-square"></i>
</a>

    <!-- Delete -->
    <form method="POST"
          action="{{ route('brands.destroy', $brand) }}"
          class="d-inline">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn p-0 border-0 bg-transparent text-secondary"
                title="Delete"
                onclick="return confirm('Are you sure you want to delete this brand?')">
            <i class="bi bi-trash"></i>
        </button>
    </form>

</td>
                                </tr>


                <!-- View Brand Modal -->
<!-- ================= VIEW BRAND MODAL ================= -->
<div class="modal fade"
     id="viewBrandModal{{ $brand->id }}"
     tabindex="-1">

    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Brand Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Brand Name</div>
                        <div>{{ $brand->name }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Status</div>
                        <div>
                            {{ $brand->status ? 'Active' : 'Inactive' }}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="fw-semibold text-muted">Description</div>
                        <div>
                            {{ $brand->description ?? 'N/A' }}
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
<!-- ================= EDIT BRAND MODAL ================= -->
<div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Brand – {{ $brand->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('brands.update', $brand->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $brand->name) }}"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="3">{{ old('description', $brand->description) }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="status"
                                       value="1"
                                       {{ $brand->status ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Active
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green">Update Brand</button>
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
                                        No brands found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <!-- ================= ADD BRAND MODAL ================= -->
<div class="modal fade" id="addBrandModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('brands.store') }}">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green">Save Brand</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
                    </table>
                </div>

 @if ($brands->total() > 10)
    <div class="d-flex justify-content-end align-items-center gap-4 px-3 py-3">

        <div class="text-muted small">
            {{ $brands->firstItem() }}–{{ $brands->lastItem() }}
            of {{ $brands->total() }}
        </div>

        <div>
            {{ $brands->links() }}
        </div>

    </div>
@endif

            </div>

        </div>

    </div>

</div>

@endsection
