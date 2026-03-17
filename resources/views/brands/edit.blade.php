@extends('layouts.app')
@section('title', 'Brands | FurnishPro')

@section('content')

<div class="card p-4">

<h4>Edit Brand</h4>

<form method="POST" action="{{ route('brands.update', $brand->id) }}">
@csrf
@method('PUT')


{{-- PRODUCT GROUP --}}
<!-- <div class="mb-3">

<label>
Product Group
<span class="text-danger fw-bold">*</span>
</label>

<select name="product_group_id"
class="form-select @error('product_group_id') is-invalid @enderror"
required>

<option value="">-- Select Product Group --</option>

@foreach($productGroups as $group)
<option value="{{ $group->id }}"
{{ old('product_group_id', $brand->product_group_id) == $group->id ? 'selected' : '' }}>
{{ $group->name }}
</option>
@endforeach

</select>

@error('product_group_id')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror

</div> -->


{{-- BRAND NAME --}}
<div class="mb-3">

<label>
Brand Name
<span class="text-danger fw-bold">*</span>
</label>

<input name="name"
class="form-control @error('name') is-invalid @enderror"
value="{{ old('name', $brand->name) }}"
required>

@error('name')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror

</div>


{{-- DESCRIPTION --}}
<div class="mb-3">

<label>Description</label>

<textarea name="description"
class="form-control">{{ old('description', $brand->description) }}</textarea>

</div>


{{-- STATUS --}}
<div class="form-check form-switch mb-3">

<input class="form-check-input"
type="checkbox"
name="status"
value="1"
{{ old('status', $brand->status) ? 'checked' : '' }}>

<label>Status</label>

</div>


{{-- BUTTONS --}}
<button class="btn btn-primary">
Update Brand
</button>

<a href="{{ route('brands.index') }}"
class="btn btn-secondary">
Cancel
</a>

</form>

</div>

@endsection
