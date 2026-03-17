@extends('layouts.app')
@section('title', 'ProductGroup | FurnishPro')

@section('content')

<div class="card p-4">
    <h4>Add Product Group</h4>

    <form method="POST" action="{{ route('group-types.store') }}">
        @csrf

        <!-- Group Type Name -->
        <div class="mb-3">
            <label>
              Product Group Name
                <span class="text-danger fw-bold">*</span>
            </label>
            <input name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   required>
            <!-- Validation error -->
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary">Save Group Type</button>
        <a href="{{ route('group-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
