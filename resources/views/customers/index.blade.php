@extends('layouts.app')
@section('title', 'Customers | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h3 class="mb-0 text-black">Customers</h3>

            {{-- <button type="button"
                class="btn btn-green"
                data-bs-toggle="modal"
                data-bs-target="#addCustomerModal">
                + Add
            </button> --}}
        </div>

        <div class="border-top"></div>

        <div class="px-4 py-3">
            <div class="border rounded-3 overflow-hidden">
<div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="fw-semibold">Customer Details</span>

    <div class="d-flex align-items-center gap-2">

        <!-- Add Customer Button -->
        <button class="btn btn-green"
                data-bs-toggle="modal"
                data-bs-target="#addCustomerModal">
            + Add Customer
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
           href="{{ route('customers.export.pdf') }}">
            <i class="bi bi-file-earmark-pdf text-danger"></i>
            <span>PDF</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('customers.export.csv') }}">
            <i class="bi bi-filetype-csv text-primary"></i>
            <span>CSV</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
           href="{{ route('customers.export.excel') }}">
            <i class="bi bi-file-earmark-excel text-success"></i>
            <span>Excel</span>
        </a>
    </li>

</ul>
        </div>

    </div>

</div>

<div class="border-top"></div>

{{-- Search Row --}}
<div class="px-3 py-2">
    <form method="GET" action="{{ route('customers.index') }}">
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

                <!-- Customers Table -->
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td>{{ $customer->customer_code }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                                <td class="text-end pe-3">

                                    <!-- View Modal Trigger -->
                                    <a href="javascript:void(0)"
                                       class="text-secondary me-2"
                                       data-bs-toggle="modal"
                                       data-bs-target="#viewCustomerModal{{ $customer->id }}">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Edit Modal Trigger -->
                                    <a href="javascript:void(0)"
                                       class="text-secondary me-2"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editCustomerModal{{ $customer->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form method="POST"
                                          action="{{ route('customers.destroy', $customer) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn p-0 border-0 bg-transparent text-secondary"
                                                onclick="return confirm('Delete this customer?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>

                            <!-- ================= VIEW CUSTOMER MODAL ================= -->
<div class="modal fade" id="viewCustomerModal{{ $customer->id }}" tabindex="-1">
    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Customer Details – {{ $customer->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Customer Code</div>
                        <div>{{ $customer->customer_code }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Name</div>
                        <div>{{ $customer->name }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Phone</div>
                        <div>{{ $customer->phone }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Alternate Phone</div>
                        <div>{{ $customer->alternate_phone ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Email</div>
                        <div>{{ $customer->email ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Address Line 1</div>
                        <div>{{ $customer->address_line1 }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Address Line 2</div>
                        <div>{{ $customer->address_line2 ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">City</div>
                        <div>{{ $customer->city }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">State</div>
                        <div>{{ $customer->state }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">PIN Code</div>
                        <div>{{ $customer->pin }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Landmark</div>
                        <div>{{ $customer->landmark ?? '-' }}</div>
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




                            <!-- ================= EDIT CUSTOMER MODAL ================= -->
                            <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Customer - {{ $customer->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Customer Code</label>
                                                        <input type="text" class="form-control" value="{{ $customer->customer_code }}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Alternate Phone</label>
                                                        <input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $customer->alternate_phone) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                        <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $customer->address_line1) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $customer->address_line2) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                                        <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                                        <input type="text" name="state" class="form-control" value="{{ old('state', $customer->state) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                                                        <input type="text" name="pin" class="form-control" value="{{ old('pin', $customer->pin) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Landmark</label>
                                                        <input type="text" name="landmark" class="form-control" value="{{ old('landmark', $customer->landmark) }}">
                                                    </div>
                                                </div>
                                                <div class="text-end mt-4">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-green">Update Customer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No customers found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================= ADD CUSTOMER MODAL ================= -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Code</label>
                            <input class="form-control" value="{{ $customerCode }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alternate Phone</label>
                            <input type="text" name="alternate_phone" value="{{ old('alternate_phone') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="form-control @error('address_line1') is-invalid @enderror">
                            @error('address_line1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="address_line2" value="{{ old('address_line2') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">PIN <span class="text-danger">*</span></label>
                            <input type="text" name="pin" value="{{ old('pin') }}" class="form-control @error('pin') is-invalid @enderror">
                            @error('pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror">
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror">
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Landmark</label>
                            <input type="text" name="landmark" value="{{ old('landmark') }}" class="form-control">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green">Save Customer</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if($errors->any())
<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
    modal.show();
});
</script>
@endif
@endpush
