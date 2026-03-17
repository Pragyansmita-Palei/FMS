@extends('layouts.app')
@section('title', 'Products | FurnishPro')

@section('content')

<div class="card p-4">
<h4>Add Product</h4>

<form method="POST" action="{{ route('products.store') }}">
@csrf

<!-- Auto Item Code -->
<div class="mb-3">
<label>Item Code <span class="text-danger fw-bold">*</span></label>
<input name="item_code"
       class="form-control"
       id="itemCode"
       data-next-code="{{ \App\Models\Product::count()+1 }}"
       readonly>

@error('item_code')
    <div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<!-- Store -->
<div class="row">
<!-- Store -->
<div class="mb-3 col-md-4">
    <label>Store <span class="text-danger fw-bold">*</span></label>
    <select name="store_id" id="storeSelect" class="form-control" required>
        <option value="">Select Store</option>
        @foreach($stores as $s)
            <option value="{{ $s->id }}" {{ old('store_id') == $s->id ? 'selected' : '' }}>
                {{ $s->storename }}
            </option>
        @endforeach
    </select>
    @error('store_id')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

<!-- Branch -->
<div class="mb-3 col-md-4">
    <label>Branch <span class="text-danger fw-bold">*</span></label>
    <select name="branch_id" id="branchSelect" class="form-control" required>
        <option value="">Select branch</option>
        {{-- branches will be loaded via AJAX --}}
    </select>
    @error('branch_id')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>


<div class="mb-3 col-md-4">
    <label>Brand <span class="text-danger fw-bold">*</span></label>

    <select name="brand_name" id="brandSelect" class="form-control" required>
        <option value="">Select or type brand</option>

        @foreach($brands as $b)
            <option value="{{ $b->name }}">{{ $b->name }}</option>
        @endforeach
    </select>

    @error('brand_name')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>


</div>



<!-- Item Name -->
<div class="row mb-3">
    <div class="col-md-8">
<label>Item Name <span class="text-danger fw-bold">*</span></label>
<input name="name"
class="form-control"
placeholder="Enter Product Name" value="{{ old('name') }}" required>
@error('name')
    <div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<!-- Quantity -->
<div class="col-md-2">
<label>Quantity <span class="text-danger fw-bold">*</span></label>
<input type="number" name="quantity" id="quantity"
       class="form-control"
       placeholder="Enter Quantity"
       value="{{ old('quantity') }}">


@error('quantity')
<div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>
<!-- Design Number -->
<div class="col-md-2">
    <label>Design Number <span class="text-danger fw-bold">*</span></label>
    <input type="text" name="design_number" id="designNumber"
           class="form-control"
           placeholder="Enter Design Number"
           value="{{ old('design_number') }}" required>
    @error('design_number')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>
    </div>

<!-- Description -->
<div class="mb-3">
<label>Description <span class="text-danger fw-bold">*</span></label>
<textarea name="description"
class="form-control"
placeholder="Enter Description"
required>{{ old('description') }}</textarea>
@error('description')
    <div class="text-danger mt-1">{{ $message }}</div>
@enderror
</div>

<!-- Group Type & Selling Unit -->
<div class="row mb-3">
   <div class="col-md-6 mb-3">
    <label>Product Group <span class="text-danger">*</span></label>

    <select name="group_type" id="groupType" class="form-control" required>
        <option value="">Select Product Group </option>

        @foreach($groupTypes as $g)
            <option value="{{ $g->id }}">
                {{ $g->name }}
            </option>
        @endforeach

        <option value="__other__">Other</option>
    </select>

    <!-- new group type -->
    <div class="mt-2 d-none" id="customGroupBox">
        <input type="text"
               name="new_group_type"
               id="customGroupInput"
               class="form-control"
               placeholder="Enter new group type">
    </div>
</div>


<div class="col-md-6 mb-3">
    <label>Selling Unit <span class="text-danger">*</span></label>

    <select name="selling_unit" id="sellingUnit" class="form-control" required>
        <option value="">Select Unit</option>
    </select>

    <!-- new selling unit -->
    <div class="mt-2 d-none" id="customUnitBox">
        <input type="text"
               name="new_selling_unit"
               id="customUnitInput"
               class="form-control"
               placeholder="Enter selling unit">
    </div>
</div>


</div>

<!-- Price Section -->
<div class="row mb-3">

    <div class="col-md-3">
        <label>MRP</label>
        <input name="mrp" id="mrp" class="form-control"
               placeholder="Enter MRP" value="{{ old('mrp') }}">
    </div>

    <div class="col-md-3">
        <label>Tax Rate %</label>
        <input name="tax_rate" id="tax_rate" class="form-control"
               placeholder="Enter Tax Rate" value="{{ old('tax_rate') }}">
    </div>

    <div class="col-md-3">
        <label>Discount %</label>
        <input name="discount" id="discount" class="form-control"
               placeholder="Enter Discount" value="{{ old('discount') }}">
    </div>

    <div class="col-md-3">
        <label>Total Price</label>
        <input name="total_price" id="total" class="form-control"
               readonly value="{{ old('total_price') }}">
    </div>

</div>


<button class="btn btn-primary">Save Item</button>
<a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>

</form>
</div>


@endsection
