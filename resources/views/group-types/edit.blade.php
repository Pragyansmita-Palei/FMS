@extends('layouts.app')
@section('title', 'ProductGroup | FurnishPro')

@section('content')

<div class="card p-4">
<h4>Edit Product Group</h4>

<form method="POST"
action="{{ route('group-types.update',$group->id) }}">
@csrf
@method('PUT')

<div class="mb-3">
<label>
Product Group Name
<span class="text-danger fw-bold">*</span>
</label>
<input name="name"
class="form-control"
value="{{ $group->name }}" required>
</div>



<button class="btn btn-primary">
Update Product Group
</button>

<a href="{{ route('group-types.index') }}"
class="btn btn-secondary">
Cancel
</a>

</form>
</div>
@endsection
