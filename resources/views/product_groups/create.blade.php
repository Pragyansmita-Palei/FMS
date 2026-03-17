@extends('layouts.app')

@section('content')



<div class="card p-4">
    <h4 class="mb-3">New Product Group</h4>

    <form method="POST" action="{{ route('product-groups.store') }}">
        @csrf

        <!-- Group Name -->
        <div class="mb-3">
            <label class="form-label">
                Group Name <span class="text-danger">*</span>
            </label>
            @error('name')
<small class="text-danger">{{ $message }}</small>
@enderror

            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="Enter Group Name"
                   required>
        </div>

        <!-- Main Product -->
        <div class="mb-3">
            <label class="form-label">
                Main Product <span class="text-danger">*</span>
            </label>
 <select name="main_product" id="main_product" class="form-control" required>
    <option value="">Select Main Product</option>
    @foreach($items as $item)
        <option
            value="{{ $item->id }}"
            data-group-type="{{ $item->group_type }}"
        >
            {{ $item->name }}
        </option>
    @endforeach
</select>



        </div>

        <!-- Addon Products -->
        <div class="mb-3">
            <label class="form-label">
                Addon Products
            </label>
<select name="addon_products[]" id="addon_products" class="form-control" multiple>
    @foreach($items as $i)
        <option
            value="{{ $i->id }}"
            data-group-type="{{ $i->group_type }}"
        >
            {{ $i->name }}
        </option>
    @endforeach
</select>


        </div>
<!-- Status -->
<div class="mb-3">
    <label class="form-label d-block">Status</label>

    <div class="form-check form-switch">
        <input class="form-check-input"
               type="checkbox"
               id="statusSwitch"
               name="status"
               value="active"
               checked>


    </div>
</div>

        <!-- Buttons -->
        <div class="mt-4">
            <button class="btn btn-primary">
                Save Group
            </button>

            <a href="{{ route('product-groups.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>




@endsection
