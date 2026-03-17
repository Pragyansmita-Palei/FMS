@extends('layouts.app')
@section('title', 'Catalogues | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Catalogues</h5>

            <!-- Trigger Add Modal -->
            {{-- <a href="#" class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addCatalogueModal">
                + Add Catalogue
            </a> --}}
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX (Catalogue details → pagination) --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Catalogue details header row --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Catalogue Details</span>
                     <!-- Trigger Add Modal -->
            <a href="#" class="btn btn-green" data-bs-toggle="modal" data-bs-target="#addCatalogueModal">
                + Add Catalogue
            </a>
                </div>

                <div class="border-top"></div>

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('catalogues.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search catalogue by name or description">
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
                                <th>Image</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($catalogues as $c)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
<td class="fw-semibold" title="{{ $c->name }}">
    {{ \Illuminate\Support\Str::words($c->name, 3, '...') }}
</td><td title="{{ $c->description }}">
    {{ \Illuminate\Support\Str::words($c->description, 3, '...') ?? '-' }}
</td>                                    <td>
                                        @if($c->image)
                                            <img src="{{ asset(config('catalogue.image_path').'/'.$c->image) }}"
                                                 class="img-thumbnail"
                                                 style="width:50px;height:50px;object-fit:cover;">
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">

                                        <!-- Edit Button -->
                                        <a href="#" class="text-decoration-none text-secondary me-2"
                                           title="Edit"
                                           data-bs-toggle="modal"
                                           data-bs-target="#editCatalogueModal{{ $c->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST"
                                              action="{{ route('catalogues.destroy', $c) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-secondary"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this catalogue?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>

                              {{-- Edit Modal per catalogue --}}
<div class="modal fade" id="editCatalogueModal{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Catalogue – {{ $c->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('catalogues.update', $c) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-control brand-select" required>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ $c->brand_id == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Catalogue Name <span class="text-danger">*</span></label>
                            <input name="name" class="form-control" value="{{ $c->name }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control">{{ $c->description }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">File</label>
                            <input type="file" name="image" class="form-control">
                            @if($c->image)
                                <img src="{{ asset(config('catalogue.image_path').'/'.$c->image) }}" width="100" class="mt-2">
                            @endif
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="status" value="1" {{ $c->status ? 'checked' : '' }}>
                                <label class="form-check-label">Status</label>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-green">
                            Update Catalogue
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No catalogues found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $catalogues->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Add Catalogue Modal --}}
<div class="modal fade" id="addCatalogueModal" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Catalogue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('catalogues.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Brand <span class="text-danger">*</span></label>
   <select name="brand_id" class="form-control brand-select" required>
    <option value="">Select Brand</option>
    @foreach($brands as $b)
        <option value="{{ $b->id }}">{{ $b->name }}</option>
    @endforeach
</select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Catalogue Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">File</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                <label class="form-check-label">Status</label>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-green">
                            Save Catalogue
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection
