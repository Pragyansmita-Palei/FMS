@extends('layouts.app')
@section('title', 'Roles | FurnishPro')

@section('content')

<div class="card shadow-sm">
<div class="card-header d-flex justify-content-between">
 <h5> Create New Role</h5>
 <a href="{{ route('admin.roles.index') }}"
  class="btn btn-light">← Back</a>
</div>

<div class="card-body">

<form action="{{ route('admin.roles.store') }}" method="POST">
@csrf

<div class="mb-3">
<label class="fw-bold">Role Name</label>
<input type="text" name="name"
 class="form-control"
 placeholder="Enter role name" required>
</div>

<hr>

<h6 class="fw-bold mb-3">Assign Permissions</h6>

<div class="row">

@foreach($permissions as $p)
<div class="col-md-4 mb-2">
 <label>
  <input type="checkbox"
   name="permissions[]"
   value="{{ $p->name }}">
  {{ $p->name }}
 </label>
</div>
@endforeach

</div>

<hr>

<button class="btn text-white btn-green"
        style="background-color:#2563eb;border-color:#2563eb;">
    ✔ Create Role
</button>

</form>

</div>
</div>

@endsection
