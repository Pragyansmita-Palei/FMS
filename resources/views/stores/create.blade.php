@extends('layouts.app')
@section('title', 'Stores | FurnishPro')

@section('content')

<div class="container-fluid">

<form method="POST" action="{{ route('stores.store') }}" id="storeForm">
@csrf

<!-- STORE CARD -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">

<h5 class="mb-4 fw-semibold">Create Store</h5>

<div class="row">

    <!-- Store Name -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Store Name <span class="text-danger">*</span></label>
        @error('storename')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input type="text" name="storename"
               value="{{ old('storename') }}"
               class="form-control @error('storename') is-invalid @enderror">
    </div>

    <!-- Phone -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Phone <span class="text-danger">*</span></label>
        @error('phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input type="text" name="phone"
               value="{{ old('phone') }}"
               class="form-control @error('phone') is-invalid @enderror">
    </div>

    <!-- Alternate Phone -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Alternate Phone</label>
        <input type="text" name="alt_phone"
               value="{{ old('alt_phone') }}"
               class="form-control">
    </div>

    <!-- Email -->
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input type="email" name="email"
               value="{{ old('email') }}"
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
           value="{{ old('address_line1') }}"
           class="form-control @error('address_line1') is-invalid @enderror"
           placeholder="Address Line 1">
</div>

<div class="mb-2">
    <label class="form-label">Address Line 2</label>
    <input name="address_line2"
           value="{{ old('address_line2') }}"
           class="form-control"
           placeholder="Address Line 2">
</div>

<div class="row">

  <!-- Pincode -->
    <div class="col-md-4 mb-2">
        <label class="form-label">Pincode <span class="text-danger">*</span></label>
        @error('pincode')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        <input name="pincode" id="pincode"
               value="{{ old('pincode') }}"
               class="form-control @error('pincode') is-invalid @enderror"
               placeholder="Enter 6 digit PIN">
    </div>
    <!-- City -->
    <div class="col-md-4 mb-2">
        <label class="form-label">City <span class="text-danger">*</span></label>
        @error('city')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        <input name="city" id="city"
               value="{{ old('city') }}"
               class="form-control @error('city') is-invalid @enderror"
               readonly>
    </div>

    <!-- State -->
    <div class="col-md-4 mb-2">
        <label class="form-label">State <span class="text-danger">*</span></label>
        @error('state')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
        <input name="state" id="state"
               value="{{ old('state') }}"
               class="form-control @error('state') is-invalid @enderror"
               readonly>
    </div>

  

</div>

<div class="mb-2">
    <label class="form-label">Landmark</label>
    <input name="landmark"
           value="{{ old('landmark') }}"
           class="form-control">
</div>
<hr>

<h6 class="fw-semibold mb-3">Contact Person Details</h6>

<div class="row">

    <div class="col-md-6 mb-3">
        <label>Name <span class="text-danger">*</span></label>
        @error('contact_name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input name="contact_name"
               value="{{ old('contact_name') }}"
               class="form-control @error('contact_name') is-invalid @enderror">
    </div>

    <div class="col-md-6 mb-3">
        <label>Phone <span class="text-danger">*</span></label>
        @error('contact_phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input name="contact_phone"
               value="{{ old('contact_phone') }}"
               class="form-control @error('contact_phone') is-invalid @enderror">
    </div>

    <div class="col-md-6 mb-3">
        <label>WhatsApp</label>
        <input name="contact_whatsapp"
               value="{{ old('contact_whatsapp') }}"
               class="form-control">
    </div>

    <div class="col-md-6 mb-3">
        <label>Email <span class="text-danger">*</span></label>
        @error('contact_email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <input name="contact_email"
               value="{{ old('contact_email') }}"
               class="form-control @error('contact_email') is-invalid @enderror">
    </div>

    <div class="col-md-12 mb-3">
        <label>Address</label>
        <textarea name="contact_address"
                  class="form-control"
                  rows="2">{{ old('contact_address') }}</textarea>
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

<!-- Container for branches -->
<div id="branchesContainer">
    <!-- Branch 1 -->
    <div class="branch-item border rounded-3 p-3 mb-3" id="branch_1">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold mb-0">Branch #1</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBranch(1)" style="display: none;" id="remove_btn_1">
                <i class="fas fa-minus"></i> Remove
            </button>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                <input type="text" name="branch_name[]" class="form-control" value="{{ old('branch_name.0') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Branch Code</label>
                <input type="text" name="branch_code[]" class="form-control" value="{{ old('branch_code.0') }}">
            </div>
        </div>

        <h6 class="fw-semibold mt-2">Branch Contact Person</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                <input type="text" name="branch_contact_name[]" class="form-control" value="{{ old('branch_contact_name.0') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Contact Phone <span class="text-danger">*</span></label>
                <input type="text" name="branch_contact_phone[]" class="form-control" value="{{ old('branch_contact_phone.0') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Contact Email</label>
                <input type="email" name="branch_contact_email[]" class="form-control" value="{{ old('branch_contact_email.0') }}">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary px-4">Save Store</button>
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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const pinInput = document.getElementById('pincode');

    pinInput.addEventListener('keyup', function () {

        // Allow only numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        let pin = this.value;

        if (pin.length === 6) {

            fetch('https://api.postalpincode.in/pincode/' + pin)
                .then(response => response.json())
                .then(data => {

                    if (data[0].Status === "Success") {

                        document.getElementById('city').value =
                            data[0].PostOffice[0].District;

                        document.getElementById('state').value =
                            data[0].PostOffice[0].State;

                    } else {

                        document.getElementById('city').value = '';
                        document.getElementById('state').value = '';
                        alert("Invalid Pincode");

                    }
                })
                .catch(() => {
                    console.log("Error fetching pincode data");
                });
        }
    });

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
