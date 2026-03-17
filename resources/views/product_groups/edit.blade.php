@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="card p-4">
    <h4 class="mb-3">Edit Product Group</h4>

    <form method="POST" action="{{ route('product-groups.update', $product_group->id) }}">
        @csrf
        @method('PUT')

        <!-- Group Name -->
        <div class="mb-3">
            <label class="form-label">Group Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $product_group->name) }}"
                   required>
        </div>

        <!-- Main Product -->
        <div class="mb-3">
            <label class="form-label">Main Product <span class="text-danger">*</span></label>
            <select name="main_product" id="main_product" class="form-control" required>
                <option value="">Select Main Product</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}"
                        {{ old('main_product', $product_group->main_product) == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Addon Products -->
        <div class="mb-3">
            <label class="form-label">Addon Products</label>
            <select name="addon_products[]" id="addon_products" class="form-control" multiple>
                @foreach($items as $item)
                    <option value="{{ $item->id }}"
                        {{ in_array($item->id, old('addon_products', $product_group->addon_products ?? [])) ? 'selected' : '' }}>
                        {{ $item->name }}
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
                       name="status"
                       {{ old('status', $product_group->status) == 1 ? 'checked' : '' }}>
            </div>
        </div>

        <button class="btn btn-primary">Update Group</button>
        <a href="{{ route('product-groups.index') }}" class="btn btn-secondary">Cancel</a>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(function () {

    $('#addon_products').select2({
        placeholder: "Select Addon Products",
        width: '100%'
    });

    $('#main_product').on('change', function () {
        let mainId = $(this).val();

        $('#addon_products option').prop('disabled', false);

        if (mainId) {
            $('#addon_products option[value="' + mainId + '"]').prop('disabled', true);
        }

        $('#addon_products').trigger('change.select2');
    });

    $('#main_product').trigger('change');
});
</script>

@endsection
