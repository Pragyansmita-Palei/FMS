@extends('layouts.app')
@section('title', 'Sales | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Sales Associate Management</h4>
        <a href="{{ route('sales_associates.index') }}" class="btn btn-outline-secondary">  ← Back</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-4">Add New Sales Associate</h5>

            <form method="POST" action="{{ route('sales_associates.store') }}">
                @csrf

                <div class="row g-3">

                    <!-- Sales Associate ID -->
                    <div class="col-md-6">
                        <label class="form-label">Sales Associate ID</label>
                        <input type="text" name="sales_id" class="form-control"
                               value="{{ 'FMS-SA-' . ($lastSalesId ?? 0 + 1) }}" readonly>
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
    <label class="form-label">
        Name <span class="text-danger">*</span>
    </label>

    @error('name')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror

    <input type="text" name="name" value="{{ old('name') }}"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Enter name" required>
</div>


                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Phone <span class="text-danger">*</span>
                        </label>
                        @error('phone')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="+91 XXXXX XXXXX">
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
                        <label class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        @error('email')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="email@example.com">
                    </div>
                    <div class="col-md-6">
    <label class="form-label">
        Password <span class="text-danger">*</span>
    </label>

    @error('password')
        <small class="text-danger d-block mb-1">{{ $message }}</small>
    @enderror

    <input type="password" name="password"
           class="form-control @error('password') is-invalid @enderror">
</div>


                    <!-- Address Line 1 -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Address Line 1 <span class="text-danger">*</span>
                        </label>
                        @error('address_line1')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror
                        <input type="text" name="address_line1" value="{{ old('address_line1') }}"
                               class="form-control @error('address_line1') is-invalid @enderror"
                               placeholder="House number, Street">
                    </div>

                    <!-- Address Line 2 -->
                    <div class="col-md-6">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                               class="form-control" placeholder="Apartment, Building">
                    </div>

                    
                  <!-- PIN -->
<div class="col-md-6">
    <label class="form-label">
        PIN Code <span class="text-danger">*</span>
    </label>

    <input type="text" name="pin" id="pin"
           value="{{ old('pin') }}"
           class="form-control @error('pin') is-invalid @enderror"
           placeholder="Enter 6 digit PIN">
</div>

<!-- City -->
<div class="col-md-6">
    <label class="form-label">
        City <span class="text-danger">*</span>
    </label>

    <input type="text" name="city" id="city"
           value="{{ old('city') }}"
           class="form-control @error('city') is-invalid @enderror"
           placeholder="City" readonly>
</div>

<!-- State -->
<div class="col-md-6">
    <label class="form-label">
        State <span class="text-danger">*</span>
    </label>

    <input type="text" name="state" id="state"
           value="{{ old('state') }}"
           class="form-control @error('state') is-invalid @enderror"
           placeholder="State" readonly>
</div>


                    <!-- Landmark -->
                    <div class="col-md-6">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="landmark" value="{{ old('landmark') }}"
                               class="form-control">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sales_associates.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('pin').addEventListener('keyup', function () {

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
                    alert("Invalid PIN Code");

                }
            });
    }
});
</script>

@endsection
