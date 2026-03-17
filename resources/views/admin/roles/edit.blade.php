@extends('layouts.app')
@section('title', 'Roles | FurnishPro')

@section('content')

<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between">
 <h5>Edit Role</h5>
 <a href="{{ route('admin.roles.index') }}"
  class="btn btn-light">← Back</a>
</div>

<div class="card-body">

<form action="{{ route('admin.roles.update',$role->id) }}"
 method="POST">
@csrf
@method('PUT')

<div class="mb-3">
<label class="fw-bold">Role Name</label>
<input type="text" name="name"
 class="form-control"
 value="{{ $role->name }}" required>
</div>

<hr>

<h6 class="fw-bold mb-3">Assign Permissions</h6>

<div class="row">

@foreach($permissions as $p)
<div class="col-md-4 mb-2">
 <label>
  <input type="checkbox"
   name="permissions[]"
   value="{{ $p->name }}"
   {{ in_array($p->name,$rolePermissions)?'checked':'' }}>
  {{ $p->name }}
 </label>
</div>
@endforeach

</div>

<hr>

<button class="btn btn-green">
🔄 Update
</button>

</form>

</div>
</div>

@endsection
