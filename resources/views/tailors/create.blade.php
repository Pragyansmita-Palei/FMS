@extends('layouts.app')
@section('title', 'Tailors | FurnishPro')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-1">Tailor Management</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-4">Add New Tailor</h5>

            <form method="POST" action="{{ route('tailors.store') }}">
                @csrf
                <div class="row g-3">

                    <!-- Tailor ID -->
                    <div class="col-md-6">
                        <label class="form-label">Tailor ID</label>
                        <input type="text" name="tailor_id" class="form-control"
                               value="{{ 'FMS-T-' . ($lastTailorId ?? 0 + 1) }}" readonly>
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label">Tailor Name <span class="text-danger">*</span></label>
                        @error('name')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Enter tailor name" required>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        @error('phone')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="+91 XXXXX XXXXX" required>
                    </div>

                    <!-- Alternate Phone -->
                    <div class="col-md-6">
                        <label class="form-label">Alternate Phone</label>
                          @error('alternate_phone')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="text" name="alternate_phone" value="{{ old('alternate_phone') }}"
                               class="form-control" placeholder="+91 XXXXX XXXXX">
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        @error('email')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="tailor@email.com">
                    </div>

                    <!-- Password -->
<div class="col-md-6">
    <label class="form-label">Password <span class="text-danger">*</span></label>
    @error('password')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror
    <input type="password" name="password"
           class="form-control @error('password') is-invalid @enderror"
           required>
</div>


                    <!-- Address Line 1 -->
                  <div class="col-md-6">
    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>

    @error('address_line1')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror

    <input type="text" name="address_line1" value="{{ old('address_line1') }}"
           class="form-control @error('address_line1') is-invalid @enderror"
           placeholder="House number, Street name" required>
</div>


                    <!-- Address Line 2 -->
                    <div class="col-md-6">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                               class="form-control" placeholder="Apartment, Building, Locality">
                    </div>

                  <!-- PIN -->
<div class="col-md-6">
    <label class="form-label">PIN Code <span class="text-danger">*</span></label>
    @error('pin')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror
    <input type="text" name="pin" id="pin"
           value="{{ old('pin') }}"
           class="form-control @error('pin') is-invalid @enderror"
           placeholder="Enter 6 digit PIN" required>
</div>

<!-- City -->
<div class="col-md-6">
    <label class="form-label">City <span class="text-danger">*</span></label>
    @error('city')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror
    <input type="text" name="city" id="city"
           value="{{ old('city') }}"
           class="form-control @error('city') is-invalid @enderror"
           placeholder="City" readonly required>
</div>

<!-- State -->
<div class="col-md-6">
    <label class="form-label">State <span class="text-danger">*</span></label>
    @error('state')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror
    <input type="text" name="state" id="state"
           value="{{ old('state') }}"
           class="form-control @error('state') is-invalid @enderror"
           placeholder="State" readonly required>
</div>

                    <!-- Landmark -->
                    <div class="col-md-6">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="landmark" value="{{ old('landmark') }}"
                               class="form-control" placeholder="Nearby landmark">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('tailors.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Tailor</button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const pinInput = document.getElementById('pin');

    pinInput.addEventListener('keyup', function () {

        let pin = this.value;

        // Allow only numbers
        this.value = this.value.replace(/[^0-9]/g, '');

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
                        alert("Invalid PIN Code");

                    }
                })
                .catch(() => {
                    console.log("Error fetching PIN data");
                });
        }
    });

});
</script>
@endsection
