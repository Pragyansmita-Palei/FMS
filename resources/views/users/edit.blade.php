@extends('layouts.app')
@section('title', 'Users | FurnishPro')

@section('content')

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <h5>Edit User</h5>

        <a href="{{ route('users.index') }}"
           class="btn btn-light">
            ← Back
        </a>
    </div>

    <div class="card-body">

        <form action="{{ route('users.update',$user->id) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="fw-bold">Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $user->name }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ $user->email }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Password (optional)</label>
                <input type="password"
                       name="password"
                       class="form-control">
            </div>

            <hr>

            <!-- Roles -->
            <h6 class="fw-bold">Assign Roles</h6>

            @foreach($roles as $role)
                <div class="form-check">
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $role->name }}"
                           class="form-check-input"
                           {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                    <label class="form-check-label">
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach

            <hr>

            <!-- User specific permissions -->
            <h6 class="fw-bold">User Specific Permissions</h6>

            <div class="row">
                @foreach($permissions as $p)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input type="checkbox"
                                   name="user_permissions[]"
                                   value="{{ $p->name }}"
                                   class="form-check-input"
                                   id="perm_{{ $p->id }}"
                                   {{ in_array($p->name, $userPermissions) ? 'checked' : '' }}>
                            <label class="form-check-label"
                                   for="perm_{{ $p->id }}">
                                {{ $p->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            <button class="btn btn-green">
                🔄 Update User
            </button>

        </form>

    </div>
</div>

@endsection