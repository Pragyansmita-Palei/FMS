@extends('layouts.app')
@section('title', 'Catalogues | FurnishPro')

@section('content')

<div class="card p-4">
<h4>Add Catalogue</h4>

<form method="POST"
action="{{ route('catalogues.store') }}"
enctype="multipart/form-data">
@csrf

<div class="mb-3">
<label>Brand <span class="text-danger fw-bold">*</span></label>
<select name="brand_id" class="form-control" required>
    <option value="">Select Brand</option>
    @foreach($brands as $b)
        <option value="{{ $b->id }}">
            {{ $b->name }}
        </option>
    @endforeach
</select>
</div>

<div class="mb-3">
<label>Catalogue Name <span class="text-danger fw-bold">*</span></label>
<input name="name" class="form-control" required>
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description" class="form-control"></textarea>
</div>
<div class="mb-3">
<label>File</label>
<input type="file"
name="image"
class="form-control"
accept="image/*">
</div>

<div class="form-check form-switch mb-3">
<input class="form-check-input"
type="checkbox" name="status" value="1" checked>
<label>Status</label>
</div>

<button class="btn btn-primary">Save</button>

<a href="{{ route('catalogues.index') }}"
class="btn btn-secondary">Cancel</a>

</form>
</div>
@endsection
