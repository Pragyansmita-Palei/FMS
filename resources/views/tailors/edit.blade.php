@extends('layouts.app')
@section('title', 'Tailors | FurnishPro')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-1">Edit Tailor</h4>
        <a href="{{ route('tailors.index') }}" class="btn btn-light">Back</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('tailors.update', $tailor) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Tailor ID</label>
                        <input type="text" class="form-control" value="{{ $tailor->tailor_id }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tailor Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $tailor->user->name }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ $tailor->phone }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alternate Phone</label>
                        <input type="text" name="alternate_phone" class="form-control" value="{{ $tailor->alternate_phone }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $tailor->user->email }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_line1" class="form-control" value="{{ $tailor->address_line1 }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-control" value="{{ $tailor->address_line2 }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ $tailor->city }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <input type="text" name="state" class="form-control" value="{{ $tailor->state }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                        <input type="text" name="pin" class="form-control" value="{{ $tailor->pin }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="landmark" class="form-control" value="{{ $tailor->landmark }}">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('tailors.index') }}" class="btn btn-light px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Tailor</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
