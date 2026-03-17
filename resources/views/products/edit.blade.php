@extends('layouts.app')
@section('title', 'Products | FurnishPro')

@section('content')

<div class="card p-4">
    <h4>Edit Product</h4>

    <form method="POST" action="{{ route('products.update', $product->id) }}">
        @csrf
        @method('PUT')

        <!-- Item Code -->
        <div class="mb-3">
            <label>Item Code</label>
            <input class="form-control" value="{{ $product->item_code }}" readonly>
        </div>

        <div class="row">

            <!-- Store -->
            <div class="mb-3 col-md-4">
                <label>Store *</label>
                <select name="store_id" id="storeSelect" class="form-control" required>
                    <option value="">Select Store</option>
                    @foreach($stores as $s)
                        <option value="{{ $s->id }}"
                            {{ $product->store_id == $s->id ? 'selected' : '' }}>
                            {{ $s->storename }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Branch -->
            <div class="mb-3 col-md-4">
                <label>Branch *</label>
                <select name="branch_id"
                        id="branchSelect"
                        class="form-control"
                        data-selected="{{ old('branch_id', $product->branch_id) }}"
                        required>
                    <option value="">Select Branch</option>
                </select>
            </div>

            <!-- Brand  (FIXED NAME) -->
            <div class="mb-3 col-md-4">
                <label>Brand *</label>
                <select name="brand_id" id="brandSelect" class="form-control" required>
                    <option value="">Select or type brand</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->name }}"
                            {{ $product->brand->name == $b->name ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Item Name / Quantity / Design -->
        <div class="row mb-3">

            <div class="col-md-8">
                <label>Item Name *</label>
                <input name="name" class="form-control"
                       value="{{ $product->name }}" required>
            </div>

            <div class="col-md-2">
                <label>Quantity *</label>
                <input type="number" name="quantity" id="quantity"
                       class="form-control"
                       value="{{ $product->quantity }}" required>
            </div>

            <div class="col-md-2">
                <label>Design Number *</label>
                <input type="text" name="design_number"
                       class="form-control"
                       value="{{ $product->design_number }}" required>
            </div>

        </div>

        <!-- Description -->
        <div class="mb-3">
            <label>Description *</label>
            <textarea name="description"
                      class="form-control"
                      required>{{ $product->description }}</textarea>
        </div>

        <!-- Group / Unit -->
        <div class="row mb-3">

            <div class="col-md-6">
                <label>Product Group *</label>
                <select name="group_type" id="groupType"
                        class="form-control" required>

                    <option value="">Select Product Group</option>

                    @foreach($groupTypes as $g)
                        <option value="{{ $g->id }}"
                            {{ $product->group_type_id == $g->id ? 'selected' : '' }}>
                            {{ $g->name }}
                        </option>
                    @endforeach

                    <option value="__other__">Other</option>
                </select>

                <div class="mt-2 d-none" id="customGroupBox">
                    <input type="text"
                           name="new_group_type"
                           id="customGroupInput"
                           class="form-control"
                           placeholder="Enter new group type">
                </div>
            </div>

            <div class="col-md-6">
                <label>Selling Unit *</label>

                <select name="selling_unit"
                        id="sellingUnit"
                        class="form-control"
                        required
                        data-selected="{{ old('selling_unit', $product->sellingUnit->unit_name ?? '') }}">
                    <option value="">Select Unit</option>
                </select>

                <div class="mt-2 d-none" id="customUnitBox">
                    <input type="text"
                           name="new_selling_unit"
                           id="customUnitInput"
                           class="form-control"
                           placeholder="Enter selling unit">
                </div>
            </div>

        </div>

        <!-- Price -->
        <div class="row mb-3">

            <div class="col-md-3">
                <label>MRP</label>
                <input name="mrp" id="mrp"
                       class="form-control"
                       value="{{ $product->mrp }}">
            </div>

            <div class="col-md-3">
                <label>Tax Rate %</label>
                <input name="tax_rate"
                       class="form-control"
                       value="{{ $product->tax_rate }}">
            </div>

            <div class="col-md-3">
                <label>Discount %</label>
                <input name="discount" id="discount"
                       class="form-control"
                       value="{{ $product->discount }}">
            </div>

            <div class="col-md-3">
                <label>Total Price</label>
                <input name="total_price" id="total"
                       class="form-control"
                       readonly
                       value="{{ $product->total_price }}">
            </div>

        </div>

        <button class="btn btn-primary">Update Item</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>

    </form>
</div>

@endsection