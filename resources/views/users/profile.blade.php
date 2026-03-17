@extends('layouts.app')
@section('title', 'Profile | FurnishPro')

@section('content')

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center"
         style="color:rgb(34, 31, 31)">

        <h5 class="mb-0">Update Profile</h5>

        <a href="{{ route('dashboard') }}"
           class="btn btn-light fw-bold">
           ← Back
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control"
                       placeholder="Enter your name">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-control"
                       placeholder="Enter your email">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Password (leave blank to keep current)
                </label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Enter new password">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Confirm Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       placeholder="Confirm new password">
            </div>

            <button class="btn text-white btn-green">
                ✔ Update Profile
            </button>

        </form>

    </div>
</div>

@endsection
