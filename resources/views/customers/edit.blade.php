@extends('layouts.app')
@section('title', 'Customers | FurnishPro')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-1">Customer Management</h4>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills bg-light p-1 rounded-3 mb-4" style="width: fit-content;">
        <li class="nav-item">
            <a class="nav-link px-4"
               href="{{ route('customers.index') }}">
                Customer List
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link px-4 active">
                Edit Customer
            </a>
        </li>
    </ul>

    <!-- Card -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-4">Edit Customer</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
<div class="col-md-6">
<label class="form-label">Customer Code</label>
<input type="text" name="customer_code"
class="form-control" value="{{ old('customer_code',$customer->customer_code) }}" readonly>
</div>
                    <!-- Customer Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Customer Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $customer->name) }}" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Phone Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $customer->phone) }}" required>
                    </div>

                    <!-- Alternate Phone -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Alternate Phone
                        </label>
                        <input type="text" name="alternate_phone" class="form-control"
                               value="{{ old('alternate_phone', $customer->alternate_phone) }}">
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Email Address
                        </label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $customer->email) }}">
                    </div>

                    <!-- Address Line 1 -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Address Line 1 <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="address_line1" class="form-control"
                               value="{{ old('address_line1', $customer->address_line1) }}" required>
                    </div>

                    <!-- Address Line 2 -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Address Line 2
                        </label>
                        <input type="text" name="address_line2" class="form-control"
                               value="{{ old('address_line2', $customer->address_line2) }}">
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <label class="form-label">
                            City <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $customer->city) }}" required>
                    </div>

                    <!-- State -->
                    <div class="col-md-6">
                        <label class="form-label">
                            State <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state', $customer->state) }}" required>
                    </div>

                    <!-- PIN -->
                    <div class="col-md-6">
                        <label class="form-label">
                            PIN Code <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="pin" class="form-control"
                               value="{{ old('pin', $customer->pin) }}" required>
                    </div>

                    <!-- Landmark -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Landmark
                        </label>
                        <input type="text" name="landmark" class="form-control"
                               value="{{ old('landmark', $customer->landmark) }}">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('customers.index') }}" class="btn btn-light px-4">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        Update Customer
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
