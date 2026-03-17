@extends('layouts.app')
@section('title', 'Units | FurnishPro')

@section('content')

<div class="card p-4">
<h4>Add Selling Unit</h4>

<form method="POST"
action="{{ route('selling-units.store') }}">
@csrf

<div class="mb-3">
<label>
Group Type
<span class="text-danger fw-bold">*</span>
</label>

<select name="group_type_id"
class="form-control" required>
<option value="">Select Group Type</option>
@foreach($groupTypes as $g)
<option value="{{ $g->id }}">
{{ $g->name }}
</option>
@endforeach
</select>
</div>

<div class="mb-3">
    <label>
        Selling Unit
        <span class="text-danger fw-bold">*</span>
    </label>
    <input name="unit_name"
           class="form-control @error('unit_name') is-invalid @enderror"
           value="{{ old('unit_name') }}"
           required>
    @error('unit_name')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>




<button class="btn btn-primary">
Save Selling Unit
</button>

<a href="{{ route('selling-units.index') }}"
class="btn btn-secondary">
Cancel
</a>

</form>
</div>
@endsection
