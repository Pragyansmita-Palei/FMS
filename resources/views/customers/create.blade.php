@extends('layouts.app')
@section('title', 'Customers | FurnishPro')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-semibold mb-1">Customer Management</h4>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills bg-light p-1 rounded-3 mb-4" style="width: fit-content;">
        <li class="nav-item">
            <a class="nav-link px-4 {{ request()->routeIs('customers.index') ? 'active' : '' }}"
               href="{{ route('customers.index') }}">
                Customer List
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link px-4 {{ request()->routeIs('customers.create') ? 'active' : '' }}"
               href="{{ route('customers.create') }}">
                Add Customer
            </a>
        </li>
    </ul>

    <!-- Card -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-4">Add New Customer</h5>

            <form method="POST" action="{{ route('customers.store') }}">
                @csrf

                <div class="row g-3">

<!-- Customer Code -->
<div class="col-md-6">
<label class="form-label">Customer Code</label>
<input type="text" name="customer_code"
class="form-control" value="{{ $customerCode }}" readonly>
</div>

<!-- Customer Name -->
<div class="col-md-6">
<label class="form-label">
Customer Name <span class="text-danger">*</span>
</label>

@error('name')
<small class="text-danger d-block mb-1">{{ $message }}</small>
@enderror

<input type="text" name="name"
value="{{ old('name') }}"
class="form-control @error('name') is-invalid @enderror"
placeholder="Enter customer name">
</div>

<!-- Phone -->
<div class="col-md-6">
<label class="form-label">
Phone Number <span class="text-danger">*</span>
</label>

@error('phone')
<small class="text-danger d-block mb-1">{{ $message }}</small>
@enderror

<input type="text" name="phone"
value="{{ old('phone') }}"
class="form-control @error('phone') is-invalid @enderror"
placeholder="+91 XXXXX XXXXX">
</div>

<!-- Alternate Phone -->
<div class="col-md-6">
<label class="form-label">Alternate Phone</label>
@error('alternate_phone')
<small class="text-danger d-block mb-1">{{ $message }}</small>
@enderror
<input type="text" name="alternate_phone"
value="{{ old('alternate_phone') }}"
class="form-control"
placeholder="+91 XXXXX XXXXX">
</div>

<!-- Email -->
<div class="col-md-6">
<label class="form-label">Email</label>
@error('email')
<small class="text-danger d-block mb-1">{{ $message }}</small>
@enderror
<input type="email" name="email"
value="{{ old('email') }}"
class="form-control"
placeholder="customer@email.com">
</div>

<!-- Address Line 1 -->
<div class="col-md-6">
<label class="form-label">
Address Line 1 <span class="text-danger">*</span>
</label>

@error('address_line1')
<small class="text-danger d-block mb-1">{{ $message }}</small>
@enderror

<input type="text" name="address_line1"
value="{{ old('address_line1') }}"
class="form-control @error('address_line1') is-invalid @enderror"
placeholder="House number, Street name">
</div>

<!-- Address Line 2 -->
<div class="col-md-6">
<label class="form-label">Address Line 2</label>
<input type="text" name="address_line2"
value="{{ old('address_line2') }}"
class="form-control"
placeholder="Apartment, Building, Locality">
</div>
<!-- PIN -->
<div class="col-md-6">
<label class="form-label">PIN Code <span class="text-danger">*</span></label>
<input type="text" name="pin" id="pin"
class="form-control" placeholder="Enter PIN code">
</div>

<!-- City -->
<div class="col-md-6">
<label class="form-label">City <span class="text-danger">*</span></label>
<input type="text" name="city" id="city"
class="form-control" readonly>
</div>

<!-- State -->
<div class="col-md-6">
<label class="form-label">State <span class="text-danger">*</span></label>
<input type="text" name="state" id="state"
class="form-control" readonly>
</div>



<!-- Landmark -->
<div class="col-md-6">
<label class="form-label">Landmark</label>
<input type="text" name="landmark"
value="{{ old('landmark') }}"
class="form-control"
placeholder="Nearby landmark">
</div>

</div>

<div class="d-flex justify-content-end gap-2 mt-4">
<a href="{{ route('customers.index') }}"
class="btn btn-light px-4">
Cancel
</a>

<button type="submit"
class="btn btn-primary px-4">
Save Customer
</button>
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
                    document.getElementById('city').value = data[0].PostOffice[0].District;
                    document.getElementById('state').value = data[0].PostOffice[0].State;
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
