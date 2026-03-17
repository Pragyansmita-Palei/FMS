@extends('layouts.app')
@section('title', 'Sales | FurnishPro')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm rounded-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center px-4 py-3 flex-nowrap">
                <h5 class="mb-0 fw-semibold">Sales Associates</h5>

            </div>

            <div class="border-top"></div>

            {{-- Search & Export --}}
            <div class="px-4 py-3">

                <div class="border rounded-3 overflow-hidden">

                    {{-- Top row: Search + Export --}}
                    {{-- Sales details header row --}}
<div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="fw-semibold">Sales Associate Details</span>

    <div class="d-flex align-items-center gap-2">

        <!-- Add Sales Button -->
        <button class="btn btn-green"
                data-bs-toggle="modal"
                data-bs-target="#addSalesAssociateModal">
            + Add Sales
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
           href="{{ route('sales_associates.export.pdf') }}">
            <i class="bi bi-file-earmark-pdf text-danger"></i>
            <span>PDF</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('sales_associates.export.csv') }}">
            <i class="bi bi-filetype-csv text-primary"></i>
            <span>CSV</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('sales_associates.export.excel') }}">
            <i class="bi bi-file-earmark-excel text-success"></i>
            <span>Excel</span>
        </a>
    </li>

</ul>
        </div>

    </div>

</div>

<div class="border-top"></div>

{{-- Search row --}}
<div class="px-3 py-2">
    <form method="GET" action="{{ route('sales_associates.index') }}">
        <div class="row">
            <div class="col-md-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control form-control-sm"
                       placeholder="Search name, phone or email">
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
                                    <th class="ps-3 fw-semibold text-secondary">#</th>
                                    <th class="fw-semibold text-secondary">Sales ID</th>
                                    <th class="fw-semibold text-secondary">Name</th>
                                    <th class="fw-semibold text-secondary">Phone</th>
                                    <th class="fw-semibold text-secondary">Email</th>
                                    <th class="fw-semibold text-secondary">City</th>
                                    <th class="fw-semibold text-secondary">State</th>
                                    <th class="fw-semibold text-secondary text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesAssociates as $sa)
                                    <tr>
                                        <td class="ps-3">{{ $loop->iteration }}</td>
                                        <td>{{ $sa->sales_id }}</td>
                                        <td class="fw-semibold text-dark">{{ $sa->user?->name ?? '-' }}</td>
                                        <td>{{ $sa->phone }}</td>
                                        <td>{{ $sa->user?->email ?? '-' }}</td>
                                        <td>{{ $sa->city ?? '-' }}</td>
                                        <td>{{ $sa->state ?? '-' }}</td>
                                        <td class="text-end pe-3">

                                            <!-- View -->
                                            <a href="javascript:void(0)" class="text-decoration-none text-secondary me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewSalesAssociateModal{{ $sa->id }}"
                                                title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Edit -->
                                            <a href="javascript:void(0)" class="text-decoration-none text-secondary me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSalesAssociateModal{{ $sa->id }}"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('sales_associates.destroy', $sa) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-secondary" title="Delete"
                                                    onclick="return confirm('Delete this sales associate?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </td>

                                    </tr>

                                    <!-- ================= EDIT SALES ASSOCIATE MODAL ================= -->
                                    <div class="modal fade" id="editSalesAssociateModal{{ $sa->id }}" tabindex="-1">
                                        <div
                                            class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Edit Sales Associate – {{ $sa->user?->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <form method="POST"
                                                        action="{{ route('sales_associates.update', $sa) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row g-3">

                                                            <div class="col-md-6">
                                                                <label class="form-label">Sales ID</label>
                                                                <input class="form-control" value="{{ $sa->sales_id }}"
                                                                    readonly>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ $sa->user?->name }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Phone <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="phone" class="form-control"
                                                                    value="{{ $sa->phone }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Alternate Phone</label>
                                                                <input type="text" name="alternate_phone"
                                                                    class="form-control"
                                                                    value="{{ $sa->alternate_phone }}">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Email <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="email" name="email" class="form-control"
                                                                    value="{{ $sa->user?->email }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Address Line 1 <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="address_line1"
                                                                    class="form-control" value="{{ $sa->address_line1 }}"
                                                                    required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Address Line 2</label>
                                                                <input type="text" name="address_line2"
                                                                    class="form-control"
                                                                    value="{{ $sa->address_line2 }}">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">City <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="city" class="form-control"
                                                                    value="{{ $sa->city }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">State <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="state" class="form-control"
                                                                    value="{{ $sa->state }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">PIN <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="pin" class="form-control"
                                                                    value="{{ $sa->pin }}" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Landmark</label>
                                                                <input type="text" name="landmark"
                                                                    class="form-control" value="{{ $sa->landmark }}">
                                                            </div>

                                                        </div>

                                                        <div class="text-end mt-4">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="btn btn-green">
                                                                Update sales
                                                            </button>
                                                        </div>

                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- View Sales Associate Modal -->
                                    <!-- ================= VIEW SALES ASSOCIATE MODAL ================= -->
                                    <div class="modal fade" id="viewSalesAssociateModal{{ $sa->id }}"
                                        tabindex="-1">
                                        <div
                                            class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Sales Associate Details – {{ $sa->user?->name ?? 'N/A' }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="row gy-3">

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Sales ID</div>
                                                            <div>{{ $sa->sales_id }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Name</div>
                                                            <div>{{ $sa->user?->name ?? '-' }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Phone</div>
                                                            <div>{{ $sa->phone }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Alternate Phone</div>
                                                            <div>{{ $sa->alternate_phone ?? '-' }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Email</div>
                                                            <div>{{ $sa->user?->email ?? '-' }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Address Line 1</div>
                                                            <div>{{ $sa->address_line1 }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Address Line 2</div>
                                                            <div>{{ $sa->address_line2 ?? '-' }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">City</div>
                                                            <div>{{ $sa->city }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">State</div>
                                                            <div>{{ $sa->state }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">PIN Code</div>
                                                            <div>{{ $sa->pin }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="fw-semibold text-muted">Landmark</div>
                                                            <div>{{ $sa->landmark ?? '-' }}</div>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            No sales associates found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <!-- ================= ADD SALES ASSOCIATE MODAL ================= -->
                            <div class="modal fade" id="addSalesAssociateModal" tabindex="-1">
                                <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New Sales Associate</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('sales_associates.store') }}">
                                                @csrf

                                                <div class="row g-3">

                                                    <div class="col-md-6">
                                                        <label class="form-label">Sales Associate ID</label>
                                                        <input class="form-control"
                                                            value="{{ 'FMS-SA-' . (($lastSalesId ?? 0) + 1) }}" readonly>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="name" value="{{ old('name') }}"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Phone <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Alternate Phone</label>
                                                        <input type="text" name="alternate_phone"
                                                            value="{{ old('alternate_phone') }}" class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" name="email" value="{{ old('email') }}"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Password <span
                                                                class="text-danger">*</span></label>
                                                        <input type="password" name="password" class="form-control"
                                                            required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Address Line 1 <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="address_line1"
                                                            value="{{ old('address_line1') }}" class="form-control"
                                                            required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="address_line2"
                                                            value="{{ old('address_line2') }}" class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">PIN <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="pin" id="modal_pin"
                                                            value="{{ old('pin') }}" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">City <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="city" value="{{ old('city') }}"
                                                            class="form-control" required>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">State <span
                                                                class="text-danger">*</span></label>

                                                        <input type="text" name="state" value="{{ old('state') }}"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Landmark</label>
                                                        <input type="text" name="landmark"
                                                            value="{{ old('landmark') }}" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="text-end mt-4">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="btn btn-green">
                                                        Save sales
                                                    </button>
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="border-top d-flex justify-content-end px-3 py-2">
                        {{ $salesAssociates->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
