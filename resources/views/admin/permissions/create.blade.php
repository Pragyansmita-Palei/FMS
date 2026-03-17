@extends('layouts.app')
@section('title', 'Permissions | FurnishPro')

@section('content')

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center"
         style=" color:rgb(34, 31, 31)">

        <h5 class="mb-0">Create Permission</h5>

        <a href="{{ route('admin.permissions.index') }}"
           class="btn btn-light fw-bold">
           ← Back
        </a>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Permission Name
                </label>

                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Enter permission name"
                       required>
            </div>

           <button class="btn text-white btn-green"
        style="background-color:#2563eb;border-color:#2563eb;">
    ✔ Submit
</button>

        </form>

    </div>
</div>

@endsection
