@extends('layouts.app')
@section('title', 'Stores | FurnishPro')

@section('content')

<div class="container-fluid">

<form method="POST" action="{{ route('stores.update', $store->id) }}" id="storeForm">
@csrf
@method('PUT')

<!-- STORE CARD -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
<h5 class="mb-4 fw-semibold">Edit Store</h5>

<div class="row">
    <!-- Store Name -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Store Name <span class="text-danger">*</span></label>
        @error('storename')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input type="text" name="storename"
               value="{{ old('storename', $store->storename) }}"
               class="form-control @error('storename') is-invalid @enderror">
    </div>

    <!-- Phone -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone <span class="text-danger">*</span></label>
        @error('phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input type="text" name="phone"
               value="{{ old('phone', $store->phone) }}"
               class="form-control @error('phone') is-invalid @enderror">
    </div>

    <!-- Alternate Phone -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Alternate Phone</label>
        <input type="text" name="alt_phone"
               value="{{ old('alt_phone', $store->alt_phone) }}"
               class="form-control">
    </div>

    <!-- Email -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input type="email" name="email"
               value="{{ old('email', $store->email) }}"
               class="form-control @error('email') is-invalid @enderror">
    </div>
</div>

<hr>




<!-- STORE ADDRESS -->
<h6 class="fw-semibold mb-3">Store Address</h6>
<div class="mb-2">
    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
    @error('address_line1')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
    <input name="address_line1"
           value="{{ old('address_line1', $store->address_line1) }}"
           class="form-control @error('address_line1') is-invalid @enderror"
           placeholder="Address Line 1">
</div>
<div class="mb-2">
    <label class="form-label">Address Line 2</label>
    <input name="address_line2"
           value="{{ old('address_line2', $store->address_line2) }}"
           class="form-control"
           placeholder="Address Line 2">
</div>

<div class="row">
    <div class="col-md-4 mb-2">
        <label class="form-label">City <span class="text-danger">*</span></label>
        @error('city')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        <input name="city"
               value="{{ old('city', $store->city) }}"
               class="form-control @error('city') is-invalid @enderror">
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label">State <span class="text-danger">*</span></label>
        @error('state')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        <input name="state"
               value="{{ old('state', $store->state) }}"
               class="form-control @error('state') is-invalid @enderror">
    </div>
    <div class="col-md-4 mb-2">
        <label class="form-label">Pincode <span class="text-danger">*</span></label>
        @error('pincode')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        <input name="pincode"
               value="{{ old('pincode', $store->pincode) }}"
               class="form-control @error('pincode') is-invalid @enderror">
    </div>
</div>

<div class="mb-2">
    <label class="form-label">Landmark</label>
    <input name="landmark"
           value="{{ old('landmark', $store->landmark) }}"
           class="form-control">
</div>
<hr>
<h6 class="fw-semibold mb-3 text-primary">Contact Person Details</h6>
<div class="row">
    <div class="col-md-6 mb-3">
        <label>Name <span class="text-danger">*</span></label>
        @error('contact_name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input name="contact_name"
               value="{{ old('contact_name', $store->contact_name) }}"
               class="form-control @error('contact_name') is-invalid @enderror">
    </div>
    <div class="col-md-6 mb-3">
        <label>Phone <span class="text-danger">*</span></label>
        @error('contact_phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input name="contact_phone"
               value="{{ old('contact_phone', $store->contact_phone) }}"
               class="form-control @error('contact_phone') is-invalid @enderror">
    </div>
    <div class="col-md-6 mb-3">
        <label>WhatsApp</label>
        <input name="contact_whatsapp"
               value="{{ old('contact_whatsapp', $store->contact_whatsapp) }}"
               class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label>Email <span class="text-danger">*</span></label>
        @error('contact_email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input name="contact_email"
               value="{{ old('contact_email', $store->contact_email) }}"
               class="form-control @error('contact_email') is-invalid @enderror">
    </div>
    <div class="col-md-12 mb-3">
        <label>Address</label>
        <textarea name="contact_address"
                  class="form-control"
                  rows="2">{{ old('contact_address', $store->contact_address) }}</textarea>
    </div>
</div>

</div>

<!-- CONTACT PERSON CARD -->
<div class="card border-0 shadow-sm rounded-4 p-4">


<!-- BRANCH DETAILS SECTION -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <h6 class="fw-semibold mb-0">Branch Details</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addNewBranch()">
        <i class="fas fa-plus"></i> Add Branch
    </button>
</div>

<div id="branchesContainer">
    @php $branchCounter = 0; @endphp
    @foreach(old('branch_name', $store->branches->pluck('branch_name')->toArray() ?? []) as $index => $branchName)
        @php $branchCounter++; $branch = $store->branches[$index] ?? null; @endphp
        <div class="branch-item border rounded-3 p-3 mb-3" id="branch_{{ $branchCounter }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-semibold mb-0">Branch #{{ $branchCounter }}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="removeBranch({{ $branchCounter }})"
                        style="{{ $branchCounter > 1 ? '' : 'display:none;' }}"
                        id="remove_btn_{{ $branchCounter }}">
                    <i class="fas fa-minus"></i> Remove
                </button>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="branch_name[]" class="form-control"
                           value="{{ old('branch_name.'.$index, $branch->branch_name ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch Code</label>
                    <input type="text" name="branch_code[]" class="form-control"
                           value="{{ old('branch_code.'.$index, $branch->branch_code ?? '') }}">
                </div>
            </div>

            <h6 class="fw-semibold mt-2">Branch Contact Person</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="branch_contact_name[]" class="form-control"
                           value="{{ old('branch_contact_name.'.$index, $branch->contact_name ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Phone <span class="text-danger">*</span></label>
                    <input type="text" name="branch_contact_phone[]" class="form-control"
                           value="{{ old('branch_contact_phone.'.$index, $branch->contact_phone ?? '') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="branch_contact_email[]" class="form-control"
                           value="{{ old('branch_contact_email.'.$index, $branch->contact_email ?? '') }}">
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary px-4">Update Store</button>
    <a href="{{ route('stores.index') }}" class="btn btn-secondary px-4">Cancel</a>
</div>
</div>

</form>

</div>

<script>
// Global counter for branch IDs
let branchCounter = 1;

function addNewBranch() {
    branchCounter++;

    // Create new branch HTML
    const newBranchId = 'branch_' + branchCounter;
    const newBranchHTML = `
        <div class="branch-item border rounded-3 p-3 mb-3" id="${newBranchId}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-semibold mb-0">Branch #${branchCounter}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBranch(${branchCounter})">
                    <i class="fas fa-minus"></i> Remove
                </button>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="branch_name[]" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch Code</label>
                    <input type="text" name="branch_code[]" class="form-control">
                </div>
            </div>

            <h6 class="fw-semibold mt-2">Branch Contact Person</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="branch_contact_name[]" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Phone <span class="text-danger">*</span></label>
                    <input type="text" name="branch_contact_phone[]" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="branch_contact_email[]" class="form-control">
                </div>
            </div>
        </div>
    `;

    // Append to container
    document.getElementById('branchesContainer').insertAdjacentHTML('beforeend', newBranchHTML);

    // Show remove button for first branch if this is the second branch
    if (branchCounter === 2) {
        document.getElementById('remove_btn_1').style.display = 'block';
    }
}

function removeBranch(id) {
    // Don't remove if it's the last branch
    const branchCount = document.querySelectorAll('.branch-item').length;
    if (branchCount <= 1) {
        alert('At least one branch is required.');
        return;
    }

    // Remove the branch
    const branchToRemove = document.getElementById('branch_' + id);
    if (branchToRemove) {
        branchToRemove.remove();
    }

    // Hide remove button for first branch if only one remains
    const remainingBranches = document.querySelectorAll('.branch-item').length;
    if (remainingBranches === 1) {
        document.getElementById('remove_btn_1').style.display = 'none';
    }

    // Renumber the branches
    renumberBranches();
}

function renumberBranches() {
    const branches = document.querySelectorAll('.branch-item');
    branches.forEach((branch, index) => {
        const newNumber = index + 1;
        const heading = branch.querySelector('h6');
        if (heading) {
            heading.textContent = 'Branch #' + newNumber;
        }

        // Update the branch ID
        branch.id = 'branch_' + newNumber;

        // Update remove button onclick if it exists
        const removeBtn = branch.querySelector('.btn-outline-danger');
        if (removeBtn) {
            removeBtn.setAttribute('onclick', 'removeBranch(' + newNumber + ')');
        }
    });
}

// Show remove button on first branch only if there's more than one branch on page load
document.addEventListener('DOMContentLoaded', function() {
    const branchCount = document.querySelectorAll('.branch-item').length;
    if (branchCount > 1) {
        document.getElementById('remove_btn_1').style.display = 'block';
    }
});
</script>

<style>
.branch-item {
    transition: all 0.3s ease;
}
.branch-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection
