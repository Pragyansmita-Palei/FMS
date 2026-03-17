@extends('layouts.app')
@section('title', 'Units | FurnishPro')

@section('content')

<div class="card p-4">
    <h4>Edit Selling Unit</h4>

    <form method="POST" action="{{ route('selling-units.update', $unit->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>
                Group Type
                <span class="text-danger fw-bold">*</span>
            </label>

            <select name="group_type_id" class="form-control" required>
                @foreach($groupTypes as $g)
                    <option value="{{ $g->id }}" {{ $unit->group_type_id == $g->id ? 'selected' : '' }}>
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
               @error('unit_name')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror

            <input name="unit_name" class="form-control" value="{{ $unit->unit_name }}" required>
        </div>

        <button class="btn btn-primary">Update Selling Unit</button>
        <a href="{{ route('selling-units.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
