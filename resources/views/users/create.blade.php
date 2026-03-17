@extends('layouts.app')
@section('title', 'Users | FurnishPro')

@section('content')

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <h5>Create User</h5>

        <a href="{{ route('users.index') }}"
           class="btn btn-light">
            ← Back
        </a>
    </div>

    <div class="card-body">

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="fw-bold">Name</label>
                <input type="text" name="name"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Email</label>
                <input type="email" name="email"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Password</label>
                <input type="password" name="password"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       required>
            </div>

            <hr>

            <h6 class="fw-bold">Assign Roles</h6>

            @foreach($roles as $role)
                <div class="form-check">
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $role->name }}"
                           class="form-check-input"
                           id="role_{{ $role->id }}">
                    <label class="form-check-label" for="role_{{ $role->id }}">
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach

            <hr>

            <h6 class="fw-bold">Assign Permissions (User specific)</h6>

            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox"
                                   name="user_permissions[]"
                                   value="{{ $permission->name }}"
                                   class="form-check-input"
                                   id="permission_{{ $permission->id }}">
                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            <button class="btn text-white btn-green"
                    style="background-color:#2563eb;border-color:#2563eb;">
                ✔ Create User
            </button>

        </form>

    </div>
</div>

@endsection