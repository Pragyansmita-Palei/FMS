@extends('layouts.app')
@section('title', 'Sales | FurnishPro')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Edit Sales Associate</h4>
        <a href="{{ route('sales_associates.index') }}" class="btn btn-light">Back to List</a>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <h5 class="fw-semibold mb-4">Update Sales Associate Details</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('sales_associates.update', $sales_associate) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Sales ID (readonly) -->
                    <div class="col-md-6">
                        <label class="form-label">Sales Associate ID</label>
                        <input type="text" class="form-control" value="{{ $sales_associate->sales_id }}" readonly>
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $sales_associate->user?->name) }}" required>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $sales_associate->phone) }}" required>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Alternate Phone -->
                    <div class="col-md-6">
                        <label class="form-label">Alternate Phone</label>
                        <input type="text" name="alternate_phone" class="form-control"
                               value="{{ old('alternate_phone', $sales_associate->alternate_phone) }}">
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email<span class="text-danger"> * </span></label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $sales_associate->user?->email) }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Address Line 1 -->
                    <div class="col-md-6">
                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_line1" class="form-control"
                               value="{{ old('address_line1', $sales_associate->address_line1) }}" required>
                    </div>

                    <!-- Address Line 2 -->
                    <div class="col-md-6">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control"
                               value="{{ old('address_line2', $sales_associate->address_line2) }}">
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $sales_associate->city) }}" required>
                    </div>

                    <!-- State -->
                    <div class="col-md-6">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state', $sales_associate->state) }}" required>
                    </div>

                    <!-- PIN -->
                    <div class="col-md-6">
                        <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                        <input type="text" name="pin" class="form-control"
                               value="{{ old('pin', $sales_associate->pin) }}" required>
                    </div>

                    <!-- Landmark -->
                    <div class="col-md-6">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="landmark" class="form-control"
                               value="{{ old('landmark', $sales_associate->landmark) }}">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sales_associates.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
