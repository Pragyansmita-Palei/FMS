@extends('layouts.app')
@section('title', 'Products | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h3 class="mb-0 text-black">Products</h3>

            <!-- changed button -->
            {{-- <button type="button"
                    class="btn btn-green"
                    data-bs-toggle="modal"
                    data-bs-target="#addProductModal">
                + Add product
            </button> --}}
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Details header row --}}
 <div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="details">Product Details</span>

    <div class="d-flex align-items-center gap-2 ">

        {{-- Store Filter --}}
        <form id="storeFilterForm" method="GET" action="{{ route('products.index') }}">

<input type="hidden" name="store_id" id="store_id" value="{{ $storeId }}">
<input type="hidden" name="search" value="{{ $search }}">
<input type="hidden" name="per_page" value="{{ $perPage }}">

<div class="dropdown">

<button class="btn btn-sm btn-light border dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        style="min-width:100px">

    {{ $storeId ? $stores->firstWhere('id',$storeId)->storename : 'All Stores' }}

</button>

<ul class="dropdown-menu dropdown-menu-end">

<li>
<a class="dropdown-item storeFilterItem"
   data-id="">
   All Stores
</a>
</li>

@foreach($stores as $store)
<li>
<a class="dropdown-item storeFilterItem"
   data-id="{{ $store->id }}">
   {{ $store->storename }}
</a>
</li>
@endforeach

</ul>
</div>

</form>

        {{-- Add Product --}}
        <button type="button"
                class="btn btn-green btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#addProductModal">
            + Add Product
        </button>

        {{-- Action --}}
        <div class="dropdown">
            <button class="btn btn-sm btn-light border dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown">
                Action
            </button>

            <ul class="dropdown-menu dropdown-menu-end p-1" style="min-width:160px">
                <li>
                    <a class="dropdown-item py-1 small"
                       href="{{ route('products.export.excel') }}">
                        📊 Export Excel
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-1 small"
                       href="{{ route('products.export.csv') }}">
                        🗂 Export CSV
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-1 small"
                       href="{{ route('products.export.pdf') }}">
                        📄 Export PDF
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-1 small"
                       href="{{ route('products.export.sample') }}">
                        📄 Sample file
                    </a>
                </li>

                <li>
                    <form action="{{ route('products.import') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <input type="file"
                               name="file"
                               id="productImportFile"
                               class="d-none"
                               onchange="this.form.submit()">

                        <label for="productImportFile"
                               class="dropdown-item py-1 small mb-0"
                               style="cursor:pointer">
                            📁 Bulk Import
                        </label>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</div>

                <div class="border-top"></div>

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="d-flex justify-content-between align-items-center">

                            <div style="width:300px">
                                <input type="text"
                                       name="search"
                                       value="{{ $search ?? '' }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search item, store or group">
                            </div>

                            <form id="perPageForm" method="GET" action="{{ route('products.index') }}">

<input type="hidden" name="search" value="{{ $search }}">
<input type="hidden" name="store_id" value="{{ $storeId }}">
<input type="hidden" name="per_page" id="per_page_value" value="{{ $perPage ?? 10 }}">

<div class="dropdown">

<button class="btn btn-sm btn-light border dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        style="min-width:80px">

    {{ $perPage ?? 10 }}

</button>

<ul class="dropdown-menu dropdown-menu-end">

<li><a class="dropdown-item perPageItem" data-value="10">10</a></li>
<li><a class="dropdown-item perPageItem" data-value="25">25</a></li>
<li><a class="dropdown-item perPageItem" data-value="50">50</a></li>
<li><a class="dropdown-item perPageItem" data-value="100">100</a></li>

</ul>

</div>

</form>

                        </div>
                    </form>
                </div>

                <div class="border-top"></div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Store</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td>{{ $p->store->storename ?? '-' }}</td>
                                <td>{{ $p->name }}</td>
<td title="{{ $p->description }}">
    {{ \Illuminate\Support\Str::words($p->description, 1, '...') }}
</td>                                <td>₹ {{ number_format($p->total_price, 2) }}</td>

                                <td class="text-end pe-3">


<a href="javascript:void(0)"
                                       class="text-decoration-none text-secondary me-2 viewProductBtn"
                                       data-id="{{ $p->id }}"
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

 <a href="javascript:void(0)"
   class="text-decoration-none text-secondary me-2 editProductBtn"
   data-id="{{ $p->id }}">
    <i class="bi bi-pencil-square"></i>
</a>

                                    <form method="POST"
                                          action="{{ route('products.destroy', $p) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn p-0 border-0 bg-transparent text-secondary"
                                                onclick="return confirm('Delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No items found
                                </td>
                            </tr>
                @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- View modal stays same --}}

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label>Item Code</label>
                        <input class="form-control" id="v_item_code" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Store</label>
                        <input class="form-control" id="v_store" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Branch</label>
                        <input class="form-control" id="v_branch" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Brand</label>
                        <input class="form-control" id="v_brand" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Item Name</label>
                        <input class="form-control" id="v_name" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Quantity</label>
                        <input class="form-control" id="v_quantity" readonly>
                    </div>

                    <div class="col-md-12">
                        <label>Description</label>
                        <textarea class="form-control" id="v_description" readonly></textarea>
                    </div>

                    <div class="col-md-6">
                        <label>Product Group</label>
                        <input class="form-control" id="v_group" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Selling Unit</label>
                        <input class="form-control" id="v_unit" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>MRP</label>
                        <input class="form-control" id="v_mrp" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Tax %</label>
                        <input class="form-control" id="v_tax" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Discount %</label>
                        <input class="form-control" id="v_discount" readonly>
                    </div>

                    <div class="col-md-3">
                        <label>Total Price</label>
                        <input class="form-control" id="v_total" readonly>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<!-- ================= EDIT PRODUCT MODAL ================= -->
<div class="modal fade" id="editProductModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Edit Product</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form method="POST" id="editProductForm">
    @csrf
    @method('PUT')

    <div class="row g-3">

        <input type="hidden" id="e_id">

        <!-- Item Code -->
        <div class="col-md-6">
            <label class="form-label">Item Code</label>
            <input id="e_item_code" class="form-control" readonly>
        </div>

        <!-- Store -->
        <div class="col-md-6">
            <label class="form-label">Store</label>
            <select name="store_id" id="e_store" class="form-control" required>
                <option value="">Select Store</option>
                @foreach($stores as $s)
                    <option value="{{ $s->id }}">{{ $s->storename }}</option>
                @endforeach
            </select>
        </div>

        <!-- Branch -->
        <div class="col-md-6">
            <label class="form-label">Branch</label>
            <select name="branch_id" id="e_branch" class="form-control" required>
                <option value="">Select Branch</option>
            </select>
        </div>

        <!-- Brand (IMPORTANT → name must be brand_id for update) -->
        <div class="col-md-6">
            <label class="form-label">Brand</label>
            <select name="brand_id" id="e_brand" class="form-control" required>
                <option value="">Select or type brand</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Item name -->
        <div class="col-md-6">
            <label class="form-label">Item Name</label>
            <input name="name" id="e_name" class="form-control" required>
        </div>

        <!-- Quantity -->
        <div class="col-md-3">
            <label class="form-label">Quantity</label>
            <input name="quantity" id="e_quantity" type="number" class="form-control" required>
        </div>

        <!-- Design -->
        <div class="col-md-3">
            <label class="form-label">Design No</label>
            <input name="design_number" id="e_design" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea name="description" id="e_description" class="form-control" rows="2"></textarea>
        </div>

        <!-- Group -->
        <div class="col-md-6">
            <label class="form-label">Product Group</label>
            <select name="group_type" id="e_group" class="form-control" required>
                <option value="">Select Product Group</option>
                @foreach($groupTypes as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
                <option value="__other__">Other</option>
            </select>

            <div class="mt-2 d-none" id="e_customGroupBox">
                <input type="text" name="new_group_type" id="e_customGroupInput"
                       class="form-control" placeholder="Enter new group">
            </div>
        </div>

        <!-- Unit -->
        <div class="col-md-6">
            <label class="form-label">Selling Unit</label>
            <select name="selling_unit" id="e_unit" class="form-control" required>
                <option value="">Select Unit</option>
            </select>

            <div class="mt-2 d-none" id="e_customUnitBox">
                <input type="text" name="new_selling_unit" id="e_customUnitInput"
                       class="form-control" placeholder="Enter new unit">
            </div>
        </div>

        <!-- MRP -->
        <div class="col-md-3">
            <label class="form-label">MRP</label>
            <input name="mrp" id="e_mrp" type="number" class="form-control">
        </div>

        <!-- Tax -->
        <div class="col-md-3">
            <label class="form-label">Tax %</label>
            <input name="tax_rate" id="e_tax" type="number" class="form-control">
        </div>

        <!-- Discount -->
        <div class="col-md-3">
            <label class="form-label">Discount %</label>
            <input name="discount" id="e_discount" type="number" class="form-control">
        </div>

        <!-- Total -->
        <div class="col-md-3">
            <label class="form-label">Total</label>
            <input id="e_total" class="form-control" readonly>
        </div>

    </div>

    <div class="text-end mt-4">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-green">Update</button>
    </div>

</form>

</div>
</div>
</div>
</div>




<div class="d-flex justify-content-end align-items-center gap-4 px-3 py-2">

    <div class="text-muted small mb-3">
        {{ $products->firstItem() }}–{{ $products->lastItem() }}
        of {{ $products->total() }}
    </div>

    <div>
        {{ $products->links() }}
    </div>

</div>

            </div>

        </div>

    </div>

</div>


<!-- ================= ADD PRODUCT MODAL ================= -->

<!-- ================= ADD PRODUCT MODAL (SMALL & CLEAN) ================= -->
<div class="modal fade" id="addProductModal" tabindex="-1">
<div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="row g-3">

                        <!-- Item Code -->
                        <div class="col-md-6">
                            <label class="form-label">Item Code</label>
                            <input name="item_code"
                                   id="itemCode"
                                   class="form-control"
                                   data-next-code="{{ \App\Models\Product::count()+1 }}"
                                   readonly>
                        </div>

                        <!-- Store -->
                        <div class="col-md-6">
                            <label class="form-label">Store <span class="text-danger">*</span></label>
                            <select name="store_id" id="storeSelect" class="form-control" required>
                                <option value="">Select Store</option>
                                @foreach($stores as $s)
                                    <option value="{{ $s->id }}">{{ $s->storename }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Branch -->
                        <div class="col-md-6">
                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" id="branchSelect" class="form-control brand-select" required>
                                <option value="">Select branch</option>
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="col-md-6">
                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                           <select name="brand_name" id="brandSelect" class="form-control">
    <option value="">Select or type brand</option>
    @foreach($brands as $b)
        <option value="{{ $b->name }}">{{ $b->name }}</option>
    @endforeach
</select>
                        </div>

                        <!-- Item Name -->
                        <div class="col-md-6">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input name="name" class="form-control" required>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" required>
                        </div>

                        <!-- Design -->
                        <div class="col-md-3">
                            <label class="form-label">Design No <span class="text-danger">*</span></label>
                            <input type="text" name="design_number" class="form-control" required>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="2" required></textarea>
                        </div>

                        <!-- Group -->
                        <div class="col-md-6">
                            <label class="form-label">Product Group <span class="text-danger">*</span></label>
                            <select name="group_type" id="groupType" class="form-control" required>
                                <option value="">Select Product Group</option>
                                @foreach($groupTypes as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                                <option value="__other__">Other</option>
                            </select>

                            <div class="mt-2 d-none" id="customGroupBox">
                                <input type="text" name="new_group_type" class="form-control"
                                       placeholder="Enter new group">
                            </div>
                        </div>

                        <!-- Unit -->
                        <div class="col-md-6">
                            <label class="form-label">Selling Unit <span class="text-danger">*</span></label>
                            <select name="selling_unit" id="sellingUnit" class="form-control" required>
                                <option value="">Select Unit</option>
                            </select>

                            <div class="mt-2 d-none" id="customUnitBox">
                                <input type="text" name="new_selling_unit" class="form-control"
                                       placeholder="Enter new unit">
                            </div>
                        </div>

                        <!-- MRP -->
                        <div class="col-md-3">
                            <label class="form-label">MRP</label>
                            <input type="number" name="mrp" id="mrp" class="form-control">
                        </div>

                        <!-- Tax -->
                        <div class="col-md-3">
                            <label class="form-label">Tax %</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="form-control">
                        </div>

                        <!-- Discount -->
                        <div class="col-md-3">
                            <label class="form-label">Discount %</label>
                            <input type="number" name="discount" id="discount" class="form-control">
                        </div>

                        <!-- Total -->
                        <div class="col-md-3">
                            <label class="form-label">Total</label>
                            <input type="number" name="total_price" id="total" class="form-control" readonly>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green">Save Product</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>



                    </form>

                </div>

            </div>

        </div>
    </div>
</div>


@endsection
