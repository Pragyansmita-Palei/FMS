@extends('layouts.app')
@section('title', 'Interiors | FurnishPro')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-1">Interior Management</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-semibold mb-0">Add New Interior</h5>

                <a href="{{ route('interiors.index') }}"
                   class="text-decoration-none text-secondary"
                   title="Back">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('interiors.store') }}">
                @csrf

                <div class="row g-3">

                    <!-- Firm Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Firm Name <span class="text-danger">*</span>
                        </label>

                        @error('firm_name')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="text"
                               name="firm_name"
                               value="{{ old('firm_name') }}"
                               class="form-control @error('firm_name') is-invalid @enderror"
                               placeholder="Enter firm name"
                               required>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Phone Number
                        </label>

                        @error('phone')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="Enter phone number">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>

                        @error('email')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="firm@email.com">
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label class="form-label">Address</label>

                        @error('address')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="text"
                               name="address"
                               value="{{ old('address') }}"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="House number, street, area">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('interiors.index') }}"
                       class="btn btn-light px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4">
                        Save Interior
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection