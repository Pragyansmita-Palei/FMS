@extends('layouts.app')
@section('title', 'Tailors | FurnishPro')

@section('content')

<!-- hidden import form -->
<form id="tailorImportForm"
      action="{{ route('tailors.import') }}"
      method="POST"
      enctype="multipart/form-data"
      class="d-none">
    @csrf
    <input type="file"
           id="tailorImportInput"
           name="file"
           accept=".xlsx,.xls,.csv"
           onchange="document.getElementById('tailorImportForm').submit();">
</form>

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Tailors</h5>

           {{-- <button class="btn btn-green"
        data-bs-toggle="modal"
        data-bs-target="#addTailorModal">
    + Add Tailor
</button> --}}
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX (Tailor details → pagination) --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Tailor details header row --}}
               <div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="fw-semibold">Tailor Details</span>

    <div class="d-flex align-items-center gap-2">

        <!-- Add Tailor Button -->
        <button class="btn btn-green"
                data-bs-toggle="modal"
                data-bs-target="#addTailorModal">
            + Add Tailor
        </button>

        <!-- Action Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                Action
            </button>
<ul class="dropdown-menu dropdown-menu-end p-1 box">

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('tailors.export.pdf') }}">
            <i class="bi bi-file-earmark-pdf text-danger"></i>
            <span>PDF</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('tailors.export.csv') }}">
            <i class="bi bi-filetype-csv text-primary"></i>
            <span>CSV</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('tailors.export.excel') }}">
            <i class="bi bi-file-earmark-excel text-success"></i>
            <span>Excel</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('tailors.import.sample') }}">
            <i class="bi bi-file-earmark-text text-secondary"></i>
            <span>Sample File</span>
        </a>
    </li>

    <li>
        <a href="#"
           class="dropdown-item py-1 small d-flex align-items-center gap-2"
           onclick="event.preventDefault(); document.getElementById('tailorImportInput').click();">
            <i class="bi bi-upload text-dark"></i>
            <span>Bulk Import</span>
        </a>
    </li>

</ul>
        </div>

    </div>

</div>

                <div class="border-top"></div>

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('tailors.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search tailor, phone or email">
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
                                <th>Tailor ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>State</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($tailors as $tailor)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td>{{ $tailor->tailor_id }}</td>
                                    <td class="fw-semibold">
                                        {{ $tailor->user->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $tailor->phone ?? '-' }}</td>
                                    <td>{{ $tailor->user->email ?? '-' }}</td>
                                    <td>{{ $tailor->city ?? '-' }}</td>
                                    <td>{{ $tailor->state ?? '-' }}</td>

                                 <td class="text-end pe-3">

    <!-- View -->
    <a href="javascript:void(0)"
       class="text-decoration-none text-secondary me-2"
       data-bs-toggle="modal"
       data-bs-target="#viewTailorModal{{ $tailor->id }}"
       title="View">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Edit -->
   <a href="javascript:void(0)"
   class="text-decoration-none text-secondary me-2"
   data-bs-toggle="modal"
   data-bs-target="#editTailorModal{{ $tailor->id }}"
   title="Edit">
    <i class="bi bi-pencil-square"></i>
</a>

    <!-- Delete -->
    <form method="POST"
          action="{{ route('tailors.destroy', $tailor) }}"
          class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn p-0 border-0 bg-transparent text-secondary"
                title="Delete"
                onclick="return confirm('Are you sure?')">
            <i class="bi bi-trash"></i>
        </button>
    </form>

</td>

                                </tr>


                                <!-- Edit Tailor Modal -->
<div class="modal fade" id="editTailorModal{{ $tailor->id }}" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Tailor - {{ $tailor->user->name ?? 'N/A' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('tailors.update', $tailor) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Tailor ID -->
                        <div class="col-md-6">
                            <label class="form-label">Tailor ID</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $tailor->tailor_id }}"
                                   readonly>
                        </div>

                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Tailor Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ $tailor->user->name }}"
                                   required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   value="{{ $tailor->phone }}"
                                   required>
                        </div>

                        <!-- Alternate Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Alternate Phone</label>
                            <input type="text"
                                   name="alternate_phone"
                                   class="form-control"
                                   value="{{ $tailor->alternate_phone }}">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ $tailor->user->email }}"
                                   required>
                        </div>

                        <!-- Password (optional on edit) -->
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Leave blank to keep current password">
                        </div>

                        <!-- Address Line 1 -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Address Line 1 <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="address_line1"
                                   class="form-control"
                                   value="{{ $tailor->address_line1 }}"
                                   required>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input type="text"
                                   name="address_line2"
                                   class="form-control"
                                   value="{{ $tailor->address_line2 }}">
                        </div>

                        <!-- PIN -->
                        <div class="col-md-6">
                            <label class="form-label">
                                PIN Code <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="pin"
                                   class="form-control"
                                   value="{{ $tailor->pin }}"
                                   required>
                        </div>

                        <!-- City -->
                        <div class="col-md-6">
                            <label class="form-label">
                                City <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="city"
                                   class="form-control"
                                   value="{{ $tailor->city }}"
                                   required>
                        </div>

                        <!-- State -->
                        <div class="col-md-6">
                            <label class="form-label">
                                State <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="state"
                                   class="form-control"
                                   value="{{ $tailor->state }}"
                                   required>
                        </div>

                        <!-- Landmark -->
                        <div class="col-md-6">
                            <label class="form-label">Landmark</label>
                            <input type="text"
                                   name="landmark"
                                   class="form-control"
                                   value="{{ $tailor->landmark }}">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-green">
                        Update Tailor
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= VIEW TAILOR MODAL ================= -->
<div class="modal fade" id="viewTailorModal{{ $tailor->id }}" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Tailor Details – {{ $tailor->user?->name ?? 'N/A' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Tailor ID</div>
                        <div>{{ $tailor->tailor_id }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Name</div>
                        <div>{{ $tailor->user?->name ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Phone</div>
                        <div>{{ $tailor->phone ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Alternate Phone</div>
                        <div>{{ $tailor->alternate_phone ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Email</div>
                        <div>{{ $tailor->user?->email ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Address Line 1</div>
                        <div>{{ $tailor->address_line1 }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Address Line 2</div>
                        <div>{{ $tailor->address_line2 ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">City</div>
                        <div>{{ $tailor->city }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">State</div>
                        <div>{{ $tailor->state }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">PIN Code</div>
                        <div>{{ $tailor->pin }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Landmark</div>
                        <div>{{ $tailor->landmark ?? '-' }}</div>
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

                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="text-center text-muted py-4">
                                        No tailors found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                                                        <div class="modal fade" id="addTailorModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Tailor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('tailors.store') }}">
                @csrf

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- Tailor ID -->
                        <div class="col-md-6">
                            <label class="form-label">Tailor ID</label>
                            <input type="text" class="form-control"
                                   value="{{ 'FMS-T-' . (($lastTailorId ?? 0) + 1) }}" readonly>
                        </div>

                        <!-- Name -->
                        <div class="col-md-6">
                            <label class="form-label">Tailor Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <!-- Alternate Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Alternate Phone</label>
                            <input type="text" name="alternate_phone" class="form-control">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <!-- Address Line 1 -->
                        <div class="col-md-6">
                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="address_line1" class="form-control" required>
                        </div>

                        <!-- Address Line 2 -->
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control">
                        </div>

                        <!-- PIN (manual) -->
                        <div class="col-md-6">
                            <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" name="pin" class="form-control"
                                   placeholder="Enter 6 digit PIN" required>
                        </div>

                        <!-- City (manual) -->
                        <div class="col-md-6">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control"
                                   placeholder="City" required>
                        </div>

                        <!-- State (manual) -->
                        <div class="col-md-6">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" class="form-control"
                                   placeholder="State" required>
                        </div>

                        <!-- Landmark -->
                        <div class="col-md-6">
                            <label class="form-label">Landmark</label>
                            <input type="text" name="landmark" class="form-control">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-green">
                        Save Tailor
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
                    </table>
                </div>

                {{-- Pagination inside same full border --}}
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $tailors->links('pagination::bootstrap-5') }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
