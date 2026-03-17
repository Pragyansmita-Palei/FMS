@extends('layouts.app')
@section('title', 'Brands | FurnishPro')

@section('content')

<div class="card p-4">

<h4>Add Brand</h4>

<form method="POST" action="{{ route('brands.store') }}">
@csrf




{{-- BRAND NAME --}}
<div class="mb-3">
<label>
Brand Name
<span class="text-danger fw-bold">*</span>
</label>

<input name="name"
class="form-control @error('name') is-invalid @enderror"
value="{{ old('name') }}"
required>

@error('name')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>


{{-- DESCRIPTION --}}
<div class="mb-3">
<label>Description</label>

<textarea name="description"
class="form-control">{{ old('description') }}</textarea>
</div>


{{-- STATUS --}}
<div class="form-check form-switch mb-3">

<input class="form-check-input"
type="checkbox"
name="status"
value="1"
{{ old('status',1) ? 'checked' : '' }}>

<label>Status</label>

</div>


{{-- BUTTONS --}}
<button class="btn btn-primary">
Save Brand
</button>

<a href="{{ route('brands.index') }}"
class="btn btn-secondary">
Cancel
</a>

</form>

</div>

@endsection
