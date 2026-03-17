@extends('layouts.app')
@section('title', 'Stores | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Stores</h5>


        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX (Store details → pagination) --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Store details header row (like Brand Details bar) --}}
                {{-- Store details header row (like Brand Details bar) --}}
<div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="fw-semibold">Store Details</span>

    <!-- Right Side Buttons -->
    <div class="d-flex align-items-center gap-2">

        <!-- Add Store -->
        <button class="btn btn-green"
            data-bs-toggle="modal"
            data-bs-target="#createStoreModal">
            + Add Store
        </button>

        <!-- Action Dropdown -->
        <div class="dropdown">

            <button class="btn btn-sm btn-light dropdown-toggle px-3"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                Action
            </button>

            <ul class="dropdown-menu dropdown-menu-end p-1" style="min-width:140px;">

                <li>
                    <a class="dropdown-item py-1 small d-flex align-items-center gap-1"
                       href="{{ route('stores.export') }}">
                       ⬇️ <span>Export</span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-1 small d-flex align-items-center gap-1"
                       href="{{ route('stores.import.sample') }}">
                       📄 <span>Sample file</span>
                    </a>
                </li>

                <li>
                    <form action="{{ route('stores.import.required') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="m-0 p-0">
                        @csrf

                        <input type="file"
                               name="file"
                               id="storeImportFile"
                               class="d-none"
                               onchange="this.form.submit()">

                        <label for="storeImportFile"
                               class="dropdown-item py-1 small d-flex align-items-center gap-1 mb-0"
                               style="cursor:pointer;">
                            📁 <span>Bulk Import</span>
                        </label>
                    </form>
                </li>

            </ul>

        </div>

    </div>

</div>


                <div class="border-top"></div>

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('stores.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search store, phone or email">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border-top"></div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover  align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Store</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($stores as $store)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $store->storename }}</td>
                                    <td>{{ $store->phone ?? '-' }}</td>
                                    <td>{{ $store->email ?? '-' }}</td>
                                    <td>{{ $store->address_line1 ?? '-' }}</td>

                                    <td class="text-end pe-3">

    <!-- View -->
    <a href="javascript:void(0)"
       class="text-decoration-none text-secondary me-2"
       data-bs-toggle="modal"
       data-bs-target="#viewStoreModal{{ $store->id }}"
       title="View">
        <i class="bi bi-eye"></i>
    </a>

    <!-- Edit -->
  <!-- Edit -->
<a href="javascript:void(0)"
   class="text-decoration-none text-secondary me-2"
   data-bs-toggle="modal"
   data-bs-target="#editStoreModal{{ $store->id }}"
   title="Edit">
    <i class="bi bi-pencil-square"></i>
</a>
    <!-- Delete -->
    <form method="POST"
          action="{{ route('stores.destroy', $store) }}"
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

<!-- View Store Modal -->
<div class="modal fade"
     id="viewStoreModal{{ $store->id }}"
     tabindex="-1">

    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Store Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row gy-3">

                    <!-- Store Name -->
                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Store Name</div>
                        <div>{{ $store->storename }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Phone</div>
                        <div>{{ $store->phone ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Alternate Phone</div>
                        <div>{{ $store->alt_phone ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Email</div>
                        <div>{{ $store->email ?? '-' }}</div>
                    </div>

                </div>

                <hr>



                <!-- Store Address -->
                <h6 class="fw-semibold mb-3">Store Address</h6>

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Address Line 1</div>
                        <div>{{ $store->address_line1 ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Address Line 2</div>
                        <div>{{ $store->address_line2 ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold text-muted">Pincode</div>
                        <div>{{ $store->pincode ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold text-muted">City</div>
                        <div>{{ $store->city ?? '-' }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold text-muted">State</div>
                        <div>{{ $store->state ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Landmark</div>
                        <div>{{ $store->landmark ?? '-' }}</div>
                    </div>

                </div>

                <hr>

                <!-- Contact Person -->
                <h6 class="fw-semibold mb-3">Contact Person Details</h6>

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Name</div>
                        <div>{{ $store->contact_name ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Phone</div>
                        <div>{{ $store->contact_phone ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">WhatsApp</div>
                        <div>{{ $store->contact_whatsapp ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Email</div>
                        <div>{{ $store->contact_email ?? '-' }}</div>
                    </div>

                    <div class="col-md-12">
                        <div class="fw-semibold text-muted">Address</div>
                        <div>{{ $store->contact_address ?? '-' }}</div>
                    </div>

                </div>

                <hr>

                  <!-- Branch Details -->
                <h6 class="fw-semibold mb-3">Branch Details</h6>

                @if(isset($store->branches) && $store->branches->count())

                    @foreach($store->branches as $index => $branch)

                        <div class="border rounded-3 p-3 mb-3">

                            <h6 class="fw-semibold mb-2">
                                Branch #{{ $index + 1 }}
                            </h6>

                            <div class="row gy-2">

                                <div class="col-md-6">
                                    <div class="fw-semibold text-muted">Branch Name</div>
                                    <div>{{ $branch->branch_name ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="fw-semibold text-muted">Branch Code</div>
                                    <div>{{ $branch->branch_code ?? '-' }}</div>
                                </div>

                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted">Contact Name</div>
                                    <div>{{ $branch->contact_name ?? '-' }}</div>
                                </div>

                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted">Contact Phone</div>
                                    <div>{{ $branch->contact_phone ?? '-' }}</div>
                                </div>

                                <div class="col-md-4">
                                    <div class="fw-semibold text-muted">Contact Email</div>
                                    <div>{{ $branch->contact_email ?? '-' }}</div>
                                </div>

                            </div>

                        </div>

                    @endforeach

                @else
                    <p class="text-muted mb-0">No branches available.</p>
                @endif

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

<!-- edit Store Modal -->


<div class="modal fade" id="editStoreModal{{ $store->id }}" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Edit Store</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST" action="{{ route('stores.update',$store->id) }}">
@csrf
@method('PUT')

<div class="row g-3">

<!-- Store Name -->
<div class="col-md-6">
<label class="form-label">Store Name <span class="text-danger">*</span></label>
<input type="text"
name="storename"
value="{{ $store->storename }}"
class="form-control">
</div>

<!-- Phone -->
<div class="col-md-6">
<label class="form-label">Phone <span class="text-danger">*</span></label>
<input type="text"
name="phone"
value="{{ $store->phone }}"
class="form-control">
</div>

<!-- Alternate Phone -->
<div class="col-md-6">
<label class="form-label">Alternate Phone</label>
<input type="text"
name="alt_phone"
value="{{ $store->alt_phone }}"
class="form-control">
</div>

<!-- Email -->
<div class="col-md-6">
<label class="form-label">Email</label>
<input type="email"
name="email"
value="{{ $store->email }}"
class="form-control">
</div>

</div>

<hr>

<h6 class="fw-semibold">Store Address</h6>

<div class="row g-3">

<div class="col-md-12">
<label>Address Line 1 <span class="text-danger">*</span></label>
<input name="address_line1"
value="{{ $store->address_line1 }}"
class="form-control">
</div>

<div class="col-md-12">
<label>Address Line 2</label>
<input name="address_line2"
value="{{ $store->address_line2 }}"
class="form-control">
</div>

<div class="col-md-4">
<label>Pincode <span class="text-danger">*</span></label>
<input name="pincode"
value="{{ $store->pincode }}"
class="form-control">
</div>

<div class="col-md-4">
<label>City <span class="text-danger">*</span></label>
<input name="city"
value="{{ $store->city }}"
class="form-control">
</div>

<div class="col-md-4">
<label>State <span class="text-danger">*</span></label>
<input name="state"
value="{{ $store->state }}"
class="form-control">
</div>

<div class="col-md-12">
<label>Landmark</label>
<input name="landmark"
value="{{ $store->landmark }}"
class="form-control">
</div>

</div>

<hr>

<h6 class="fw-semibold">Contact Person</h6>

<div class="row g-3">

<div class="col-md-6">
<label>Name <span class="text-danger">*</span></label>
<input name="contact_name"
value="{{ $store->contact_name }}"
class="form-control">
</div>

<div class="col-md-6">
<label>Phone <span class="text-danger">*</span></label>
<input name="contact_phone"
value="{{ $store->contact_phone }}"
class="form-control">
</div>

<div class="col-md-6">
<label>WhatsApp</label>
<input name="contact_whatsapp"
value="{{ $store->contact_whatsapp }}"
class="form-control">
</div>

<div class="col-md-6">
<label>Email <span class="text-danger">*</span></label>
<input name="contact_email"
value="{{ $store->contact_email }}"
class="form-control">
</div>

<div class="col-md-12">
<label>Address</label>
<textarea name="contact_address"
class="form-control"
rows="2">{{ $store->contact_address }}</textarea>
</div>

</div>

<hr>

<!-- Branch Section -->
<div class="d-flex justify-content-between align-items-center mb-3 mt-3">
<h6 class="fw-semibold mb-0">Branch Details</h6>

<button type="button"
class="btn btn-sm btn-outline-primary"
onclick="addNewBranch('branchesContainer{{ $store->id }}')">
<i class="fas fa-plus"></i> Add Branch
</button>
</div>

<div id="branchesContainer{{ $store->id }}">

@foreach($store->branches as $index => $branch)

<div class="branch-item border rounded-3 p-3 mb-3">

<input type="hidden" name="branch_id[]" value="{{ $branch->id }}">

<div class="row g-3">

<div class="col-md-6">
<label>Branch Name <span class="text-danger">*</span></label>
<input type="text"
name="branch_name[]"
value="{{ $branch->branch_name }}"
class="form-control">
</div>

<div class="col-md-6">
<label>Branch Code</label>
<input type="text"
name="branch_code[]"
value="{{ $branch->branch_code }}"
class="form-control">
</div>

<div class="col-md-4">
<label>Contact Name <span class="text-danger">*</span></label>
<input type="text"
name="branch_contact_name[]"
value="{{ $branch->contact_name }}"
class="form-control">
</div>

<div class="col-md-4">
<label>Phone <span class="text-danger">*</span></label>
<input type="text"
name="branch_contact_phone[]"
value="{{ $branch->contact_phone }}"
class="form-control">
</div>

<div class="col-md-4">
<label>Email</label>
<input type="email"
name="branch_contact_email[]"
value="{{ $branch->contact_email }}"
class="form-control">
</div>

</div>
</div>

@endforeach

</div>

<div class="text-end mt-4">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-green">Update Store</button>
</div>

</form>

</div>
</div>
</div>
</div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No store found
                                    </td>
                                </tr>



                            @endforelse
                        </tbody>
                    </table>

     {{-- add store modal --}}
<div class="modal fade" id="createStoreModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Create Store</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST" action="{{ route('stores.store') }}">
@csrf

<div class="row g-3">

<!-- Store Name -->
<div class="col-md-6">
<label class="form-label">Store Name <span class="text-danger">*</span></label>

@error('storename')
<small class="text-danger">{{ $message }}</small>
@enderror

<input type="text"
name="storename"
value="{{ old('storename') }}"
class="form-control @error('storename') is-invalid @enderror">
</div>

<!-- Phone -->
<div class="col-md-6">
<label class="form-label">Phone <span class="text-danger">*</span></label>

@error('phone')
<small class="text-danger">{{ $message }}</small>
@enderror

<input type="text"
name="phone"
value="{{ old('phone') }}"
class="form-control @error('phone') is-invalid @enderror">
</div>

<!-- Alternate Phone -->
<div class="col-md-6">
<label class="form-label">Alternate Phone</label>

<input type="text"
name="alt_phone"
value="{{ old('alt_phone') }}"
class="form-control">
</div>

<!-- Email -->
<div class="col-md-6">
<label class="form-label">Email</label>

@error('email')
<small class="text-danger">{{ $message }}</small>
@enderror

<input type="email"
name="email"
value="{{ old('email') }}"
class="form-control @error('email') is-invalid @enderror">
</div>

</div>

<hr>

<h6 class="fw-semibold">Store Address</h6>

<div class="row g-3">

<!-- Address 1 -->
<div class="col-md-12">
<label class="form-label">Address Line 1 <span class="text-danger">*</span></label>

@error('address_line1')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="address_line1"
value="{{ old('address_line1') }}"
class="form-control @error('address_line1') is-invalid @enderror">
</div>

<!-- Address 2 -->
<div class="col-md-12">
<label class="form-label">Address Line 2</label>

<input name="address_line2"
value="{{ old('address_line2') }}"
class="form-control">
</div>

<!-- Pincode -->
<div class="col-md-4">
<label class="form-label">Pincode <span class="text-danger">*</span></label>

@error('pincode')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="pincode"
id="pincode"
value="{{ old('pincode') }}"
class="form-control @error('pincode') is-invalid @enderror">
</div>

<!-- City -->
<div class="col-md-4">
<label class="form-label">City <span class="text-danger">*</span></label>

@error('city')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="city"
id="city"
value="{{ old('city') }}"
class="form-control @error('city') is-invalid @enderror">
</div>

<!-- State -->
<div class="col-md-4">
<label class="form-label">State <span class="text-danger">*</span></label>

@error('state')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="state"
id="state"
value="{{ old('state') }}"
class="form-control @error('state') is-invalid @enderror">
</div>

<!-- Landmark -->
<div class="col-md-12">
<label class="form-label">Landmark</label>

<input name="landmark"
value="{{ old('landmark') }}"
class="form-control">
</div>

</div>

<hr>

<h6 class="fw-semibold">Contact Person</h6>

<div class="row g-3">

<!-- Contact Name -->
<div class="col-md-6">
<label>Name <span class="text-danger">*</span></label>

@error('contact_name')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="contact_name"
value="{{ old('contact_name') }}"
class="form-control @error('contact_name') is-invalid @enderror">
</div>

<!-- Contact Phone -->
<div class="col-md-6">
<label>Phone <span class="text-danger">*</span></label>

@error('contact_phone')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="contact_phone"
value="{{ old('contact_phone') }}"
class="form-control @error('contact_phone') is-invalid @enderror">
</div>

<!-- WhatsApp -->
<div class="col-md-6">
<label>WhatsApp</label>

<input name="contact_whatsapp"
value="{{ old('contact_whatsapp') }}"
class="form-control">
</div>

<!-- Contact Email -->
<div class="col-md-6">
<label>Email <span class="text-danger">*</span></label>

@error('contact_email')
<small class="text-danger">{{ $message }}</small>
@enderror

<input name="contact_email"
value="{{ old('contact_email') }}"
class="form-control @error('contact_email') is-invalid @enderror">
</div>

<!-- Contact Address -->
<div class="col-md-12">
<label>Address</label>

<textarea name="contact_address"
class="form-control"
rows="2">{{ old('contact_address') }}</textarea>
</div>

</div>

<hr>

<!-- Branch -->
<div class="d-flex justify-content-between align-items-center mb-3 mt-2">
<h6 class="fw-semibold mb-0">Branch Details</h6>

<button type="button"
class="btn btn-sm btn-outline-primary"
onclick="addNewBranch()">
<i class="fas fa-plus"></i> Add Branch
</button>
</div>

<div id="branchesContainer">

<div class="branch-item border rounded-3 p-3 mb-3" id="branch_1">

<div class="row g-3">

<div class="col-md-6">
<label>Branch Name <span class="text-danger">*</span></label>

<input type="text"
name="branch_name[]"
class="form-control"
value="{{ old('branch_name.0') }}">
</div>

<div class="col-md-6">
<label>Branch Code</label>

<input type="text"
name="branch_code[]"
class="form-control"
value="{{ old('branch_code.0') }}">
</div>

<div class="col-md-4">
<label>Contact Name <span class="text-danger">*</span></label>

<input type="text"
name="branch_contact_name[]"
class="form-control"
value="{{ old('branch_contact_name.0') }}">
</div>

<div class="col-md-4">
<label>Phone <span class="text-danger">*</span></label>

<input type="text"
name="branch_contact_phone[]"
class="form-control"
value="{{ old('branch_contact_phone.0') }}">
</div>

<div class="col-md-4">
<label>Email</label>

<input type="email"
name="branch_contact_email[]"
class="form-control"
value="{{ old('branch_contact_email.0') }}">
</div>

</div>

</div>

</div>

<div class="text-end mt-4">
<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-green">Save Store</button>
</div>

</form>

</div>
</div>
</div>
</div>
                </div>

                {{-- Pagination inside same full border --}}
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $stores->links('pagination::bootstrap-5') }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection


