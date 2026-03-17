@extends('layouts.app')
@section('title', 'Labours | FurnishPro')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-1">Labour Management</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <!-- Title row with back arrow -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-semibold mb-0">Edit Labour</h5>

                <a href="{{ route('labours.index') }}"
                   class="text-decoration-none text-secondary"
                   title="Back">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
            </div>

            <form method="POST" action="{{ route('labours.update', $labour->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Labour Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Labour Name <span class="text-danger">*</span>
                        </label>

                        @error('labour_name')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="text"
                               name="labour_name"
                               value="{{ old('labour_name', $labour->labour_name) }}"
                               class="form-control @error('labour_name') is-invalid @enderror"
                               required>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Phone Number <span class="text-danger">*</span>
                        </label>

                        @error('phone_number')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="text"
                               name="phone_number"
                               value="{{ old('phone_number', $labour->phone_number) }}"
                               class="form-control @error('phone_number') is-invalid @enderror"
                               required>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>

                        @error('email')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="email"
                               name="email"
                               value="{{ old('email', $labour->email) }}"
                               class="form-control @error('email') is-invalid @enderror">
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Address <span class="text-danger">*</span>
                        </label>

                        @error('address')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="text"
                               name="address"
                               value="{{ old('address', $labour->address) }}"
                               class="form-control @error('address') is-invalid @enderror"
                               required>
                    </div>

                    <!-- Rate Type -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Rate Type <span class="text-danger">*</span>
                        </label>

                        @error('rate_type')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <select name="rate_type"
                                class="form-select @error('rate_type') is-invalid @enderror"
                                required>
                            <option value="">Select</option>
                            <option value="day"
                                {{ old('rate_type', $labour->rate_type) == 'day' ? 'selected' : '' }}>
                                Per Day
                            </option>
                            <option value="hour"
                                {{ old('rate_type', $labour->rate_type) == 'hour' ? 'selected' : '' }}>
                                Per Hour
                            </option>
                        </select>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <label class="form-label">
                            Price <span class="text-danger">*</span>
                        </label>

                        @error('price')
                            <small class="text-danger d-block mb-1">{{ $message }}</small>
                        @enderror

                        <input type="number"
                               step="0.01"
                               name="price"
                               value="{{ old('price', $labour->price) }}"
                               class="form-control @error('price') is-invalid @enderror"
                               placeholder="Enter price"
                               required>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('labours.index') }}"
                       class="btn btn-light px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4">
                        Update Labour
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection