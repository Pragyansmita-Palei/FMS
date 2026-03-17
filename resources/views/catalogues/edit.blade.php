@extends('layouts.app')
@section('title', 'Catalogues | FurnishPro')

@section('content')

<div class="card p-4">
<h4>Edit Catalogue</h4>

<form method="POST"
action="{{ route('catalogues.update',$catalogue) }}"
enctype="multipart/form-data">

@csrf @method('PUT')

<div class="mb-3">
<label>Brand <span class="text-danger fw-bold">*</span></label>
<select name="brand_id" class="form-control" required>
@foreach($brands as $b)
<option value="{{ $b->id }}"
{{ $catalogue->brand_id==$b->id?'selected':'' }}>
{{ $b->name }}
</option>
@endforeach
</select>
</div>

<div class="mb-3">
<label>Catalogue Name <span class="text-danger fw-bold">*</span></label>
<input name="name"
class="form-control"
value="{{ $catalogue->name }}" required>
</div>

<div class="mb-3">
<label>Description</label>
<textarea name="description"
class="form-control">{{ $catalogue->description }}</textarea>
</div>
<div class="mb-3">
<label>File</label>
<input type="file" name="image" class="form-control">

@if($catalogue->image)
<img src="{{ asset('uploads/catalogues/'.$catalogue->image) }}"
width="100" class="mt-2">
@endif
</div>

<div class="form-check form-switch mb-3">
<input class="form-check-input"
type="checkbox" name="status"
value="1"
{{ $catalogue->status?'checked':'' }}>
<label>Status</label>
</div>

<button class="btn btn-primary">
Update
</button>

<a href="{{ route('catalogues.index') }}"
class="btn btn-secondary">
Cancel
</a>

</form>
</div>
@endsection
