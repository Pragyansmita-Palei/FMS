@php
use Illuminate\Support\Str;
$materialIndex = 0;
@endphp

<div class="container-fluid mt-3">
    <form method="POST" action="{{ route('projects.storeStep3') }}">
        @csrf
        <input type="hidden" name="project_id" value="{{ $projectId }}">

        {{-- FILTER AND ADD IN ONE ROW AT THE RIGHT END --}}
        <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
            <!-- Filter Dropdown -->
            <div class="custom-dropdown">
                <button class="btn btn-light border dropdown-toggle" type="button" id="areaFilterButton">
                    <i class="bi bi-funnel me-2"></i>Filter by Area
                </button>
                <div class="custom-dropdown-menu shadow-sm" id="areaFilterMenu">
                    <a class="custom-dropdown-item active" href="#" data-area="all">
                        <i class="bi bi-grid me-2"></i>All Areas
                    </a>
                    <div class="dropdown-divider"></div>
                    @foreach ($measurements->groupBy(fn($m)=>$m->area->name) as $areaName => $rows)
                    <a class="custom-dropdown-item" href="#" data-area="{{ Str::slug($areaName) }}">
                        <i class="bi bi-folder me-2"></i>{{ $areaName }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-md-12">
            @foreach ($measurements->groupBy(fn($m)=>$m->area->name) as $areaName => $areaMeasurements)
            <div class="card border-0 shadow-sm mb-4 area-card" data-area="{{ Str::slug($areaName) }}">
                <div class="card-header">
                    <div class="area-header">
                        <div class="area-title-section">
                            <h4 class="area-title">{{ $areaName }}</h4>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @foreach ($areaMeasurements->groupBy('reference') as $reference => $rows)
                    @php
                    $savedMaterials = $materialsByArea[$areaName][$reference] ?? collect();
                    @endphp

                    <div class="material-section">
                        {{-- Reference Header with Add Button --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-1 fw-semibold">{{ $reference }}</h5>
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="bi bi-arrow-left-right me-1"></i>{{ $rows->first()->width }} x {{ $rows->first()->height }} {{ $rows->first()->unit }}</span>
                                    @if($rows->first()->qty)
                                    <span><i class="bi bi-files me-1"></i>Qty: {{ $rows->first()->qty }}</span>
                                    @endif
                                </div>
                            </div>

                            <button type="button"
                                class="btn-outline-primary add-material px-3"
                                >
                                <i class="bi bi-plus-circle me-1"></i>
                                Add Material
                            </button>
                        </div>

                        {{-- Table Header --}}
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product <span class="text-danger">*</span></th>
                                        <th>Brand <span class="text-danger">*</span></th>
                                        <th>Catalogue <span class="text-danger">*</span></th>
                                        <th>Design No</th>
                                        <th>MRP ($)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="material-rows">
                                    @if($savedMaterials->count())
                                        @foreach($savedMaterials as $mat)
                                        @php $materialIndex++; @endphp

                                        <tr class="material-row" data-index="{{ $materialIndex }}">
                                            <td>
                                                <input type="hidden"
                                                    name="materials[{{ $materialIndex }}][measurement_id]"
                                                    value="{{ $mat->measurement_id }}">
                                                <select class="form-select form-select-sm product-select material-select"
                                                        name="materials[{{ $materialIndex }}][product_id]">
                                                    <option value="">Select product...</option>
                                                    @foreach($products as $p)
                                                    <option value="{{ $p->id }}"
                                                            data-brand="{{ $p->brand_id }}"
                                                            data-design="{{ $p->design_number }}"
                                                            data-mrp="{{ $p->mrp }}"
                                                            @selected($mat->product_id == $p->id)>
                                                        {{ $p->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm brand-select material-select"
                                                        name="materials[{{ $materialIndex }}][brand_id]">
                                                    <option value="">Select company...</option>
                                                    @foreach($brands as $b)
                                                    <option value="{{ $b->id }}"
                                                            data-id="{{ $b->id }}"
                                                            @selected($mat->brand_id == $b->id)>
                                                        {{ $b->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="materials[{{ $materialIndex }}][catalogue_id]"
                                                        class="form-select form-select-sm catalogue-select material-select">
                                                    <option value="">Select catalogue...</option>
                                                    @foreach($catalogues as $c)
                                                    <option value="{{ $c->id }}"
                                                            data-brand="{{ $c->brand_id }}"
                                                            @selected($mat->catalogue_id == $c->id)>
                                                        {{ $c->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm design-select material-select"
                                                        name="materials[{{ $materialIndex }}][design_no]">
                                                    <option value="">Select design...</option>
                                                    @if($mat->design_no)
                                                    <option value="{{ $mat->design_no }}" selected>{{ $mat->design_no }}</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control form-control-sm"
                                                    name="materials[{{ $materialIndex }}][mrp]"
                                                    placeholder="0.00"
                                                    value="{{ $mat->mrp }}">
                                            </td>
                                            <td>
                                                <button type="button" class="remove-row-btn">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        @php $materialIndex++; @endphp

                                        <tr class="material-row" data-index="{{ $materialIndex }}">
                                            <td>
                                                <input type="hidden"
                                                    name="materials[{{ $materialIndex }}][measurement_id]"
                                                    value="{{ $rows->first()->id }}">
                                                <select name="materials[{{ $materialIndex }}][product_id]"
                                                        class="form-select form-select-sm product-select material-select">
                                                    <option value="">Select product...</option>
                                                    @foreach($products as $p)
                                                    <option value="{{ $p->id }}"
                                                            data-brand="{{ $p->brand_id }}"
                                                            data-design="{{ $p->design_number }}"
                                                            data-mrp="{{ $p->mrp }}">
                                                        {{ $p->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="materials[{{ $materialIndex }}][brand_id]"
                                                        class="form-select form-select-sm brand-select material-select">
                                                    <option value="">Select company...</option>
                                                    @foreach($brands as $b)
                                                    <option value="{{ $b->id }}" data-id="{{ $b->id }}">
                                                        {{ $b->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="materials[{{ $materialIndex }}][catalogue_id]"
                                                        class="form-select form-select-sm catalogue-select material-select">
                                                    <option value="">Select catalogue...</option>
                                                    @foreach($catalogues as $c)
                                                    <option value="{{ $c->id }}" data-brand="{{ $c->brand_id }}">
                                                        {{ $c->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm design-select material-select"
                                                        name="materials[{{ $materialIndex }}][design_no]">
                                                    <option value="">Select design...</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control form-control-sm"
                                                    name="materials[{{ $materialIndex }}][mrp]"
                                                    placeholder="0.00">
                                            </td>
                                            <td>
                                                <button type="button" class="remove-row-btn">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            {{-- SAVE BUTTON --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn-green">
                    Save & Continue
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="ms-2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Modern styling with card-based design */
    .area-section {
        animation: slideIn 0.3s ease-in-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card styling - NO HOVER EFFECTS */
    .card {
        border-radius: 16px;
        transition: none;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid rgba(0, 0, 0, 0.03) !important;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transform: none;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }

    .card-body {
        padding: 0;
    }

    /* Area header styling */
    .area-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .area-title-section {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .area-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #212529;
        margin: 0;
        text-transform: capitalize;
    }

    /* Material section styling */
    .material-section {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .material-section:last-child {
        border-bottom: none;
    }

    /* Table styling - NO HOVER EFFECTS */
    .table {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        background-color: #ffffff;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* NO TABLE ROW HOVER EFFECT */
    .table tbody tr:hover {
        background-color: transparent;
    }

    .table tbody tr:hover td {
        background-color: transparent;
    }

    /* Form control styling - NO HOVER EFFECTS */
    .form-control,
    .form-select {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        transition: none;
        font-size: 0.9rem;
        background-color: #ffffff;
    }

    .form-control-sm,
    .form-select-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 8px;
        height: auto;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #238254;
        box-shadow: 0 0 0 0.25rem rgba(35, 130, 84, 0.15);
        background-color: #fff;
        outline: none;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #e9ecef;
        background-color: #ffffff;
    }

    /* Custom Dropdown Styling */
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }

    .custom-dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1000;
        display: none;
        min-width: 200px;
        padding: 0.5rem 0;
        margin-top: 0.125rem;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 10px;
        max-height: 400px;
        overflow-y: auto;
    }

    .custom-dropdown-menu.show {
        display: block;
    }

    .custom-dropdown-item {
        display: block;
        width: 100%;
        padding: 0.6rem 1.2rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        transition: all 0.2s;
        cursor: pointer;
    }

    .custom-dropdown-item:hover {
        background-color: #f0f7ff !important;
        color: #1a832a5c !important;
    }

    .custom-dropdown-item.active {
        background-color: #238254 !important;
        color: white !important;
    }

    .dropdown-divider {
        height: 0;
        margin: 0.3rem 0;
        overflow: hidden;
        border-top: 1px solid #e9ecef;
    }

    /* Button styling - NO HOVER EFFECTS */
    .btn-green {
        background-color: #238254;
        border-color: #238254;
        color: white;
        border-radius: 10px;
        font-weight: 500;
        padding: 0.6rem 1.5rem;
        transition: none;
        border: none;
    }

    .btn-green:hover {
        background-color: #238254;
        border-color: #238254;
        color: white;
    }

    .btn-outline-primary i {
        font-size: 1.1rem;
    }

    /* Remove row button - NO HOVER EFFECTS */
    .remove-row-btn {
        background-color: transparent;
        border: none;
        color: #dc3545;
        padding: 8px;
        border-radius: 4px;
        cursor: pointer;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .remove-row-btn:hover {
        background-color: transparent;
        color: #dc3545;
    }

    .remove-row-btn i {
        font-size: 1.1rem;
    }

    /* Required field indicator */
    .text-danger {
        color: #dc3545 !important;
        font-size: 0.8rem;
        margin-left: 2px;
    }

    /* Header styling */
    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Border styling */
    .border {
        border-color: #e9ecef !important;
    }

    .rounded-3 {
        border-radius: 10px !important;
    }

    /* Icon styling */
    .bi {
        vertical-align: middle;
    }

    /* Better option display */
    select option {
        white-space: normal;
        padding: 8px;
    }

    /* Gap utilities */
    .gap-2 {
        gap: 0.5rem;
    }
    .gap-3 {
        gap: 1rem;
    }
    .gap-4 {
        gap: 1.5rem;
    }

    /* Ensure proper alignment */
    .d-flex {
        display: flex;
    }
    .align-items-center {
        align-items: center;
    }
    .justify-content-end {
        justify-content: flex-end;
    }

    /* Table responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Invalid field styling */
    .is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff8f8 !important;
    }

    /* Select2 customization */
    .select2-container--default .select2-selection--single {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        height: 38px;
        padding: 5px 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        color: #212529;
    }

    .select2-dropdown {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize Select2 for all material selects
    $('.material-select').select2({
        width: '100%'
    });

    // Handle product selection change with Select2
    $(document).on('select2:select', '.product-select', function(e) {
        const selected = e.params.data;
        const row = $(this).closest('.material-row');

        // Get the selected option's data attributes
        const selectedOption = $(this).find('option[value="' + selected.id + '"]');
        const brandId = selectedOption.data('brand');
        const designNo = selectedOption.data('design');
        const mrp = selectedOption.data('mrp');

        const brandSelect = row.find('.brand-select');
        const catalogueSelect = row.find('.catalogue-select');
        const designSelect = row.find('.design-select');
        const mrpInput = row.find('input[name*="[mrp]"]');

        // BRAND AUTO-SELECT
        if (brandSelect.length) {
            // First, filter and show/hide brand options
            brandSelect.find('option').each(function() {
                const bid = $(this).data('id');
                if (!bid) {
                    $(this).show();
                    return;
                }
                if (bid == brandId) {
                    $(this).show();
                    $(this).prop('selected', true);
                } else {
                    $(this).hide();
                    $(this).prop('selected', false);
                }
            });

            // Trigger change to update Select2
            brandSelect.trigger('change');
        }

        // CATALOGUE FILTER
        if (catalogueSelect.length) {
            catalogueSelect.find('option').each(function() {
                const cb = $(this).data('brand');
                if (!cb) {
                    $(this).show();
                    return;
                }
                if (cb == brandId) {
                    $(this).show();
                } else {
                    $(this).hide();
                    $(this).prop('selected', false);
                }
            });

            // Reset catalogue selection
            catalogueSelect.val('');
            catalogueSelect.trigger('change');
        }

        // DESIGN AUTO
        if (designSelect.length) {
            designSelect.empty();
            designSelect.append('<option value="">Select design...</option>');
            if (designNo) {
                designSelect.append('<option value="' + designNo + '" selected>' + designNo + '</option>');
            }
            designSelect.trigger('change');
        }

        // MRP AUTO
        if (mrpInput.length && mrp) {
            mrpInput.val(mrp);
        }
    });

    // Handle brand change - filter catalogues
    $(document).on('select2:select', '.brand-select', function(e) {
        const selected = e.params.data;
        const row = $(this).closest('.material-row');
        const brandId = selected.id;
        const catalogueSelect = row.find('.catalogue-select');

        if (catalogueSelect.length) {
            catalogueSelect.find('option').each(function() {
                const cb = $(this).data('brand');
                if (!cb) {
                    $(this).show();
                    return;
                }
                if (cb == brandId) {
                    $(this).show();
                } else {
                    $(this).hide();
                    $(this).prop('selected', false);
                }
            });

            catalogueSelect.val('');
            catalogueSelect.trigger('change');
        }
    });

    // Modal Select2 initialization
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.material-select').select2({
            dropdownParent: $(this),
            width: '100%'
        });
    });

    // Sales associate select
    $('.sales-associate-select').select2({
        width: '100%'
    });

    // Add Unit Modal
    $('#addUnitModal').on('shown.bs.modal', function () {
        $(this).find('select').select2({
            dropdownParent: $('#addUnitModal'),
            width: '100%'
        });
    });

    // Add Selling Unit Modal
    $('#addSellingUnitModal select').select2({
        dropdownParent: $('#addSellingUnitModal'),
        width: '100%'
    });

    // AREA FILTER FUNCTIONALITY
    // Custom Dropdown Toggle
    const dropdownButton = document.getElementById('areaFilterButton');
    const dropdownMenu = document.getElementById('areaFilterMenu');

    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    initializeFilter();

    function initializeFilter() {
        const filterItems = document.querySelectorAll('.custom-dropdown-item');
        const areaCards = document.querySelectorAll('.area-card');
        const filterButton = document.getElementById('areaFilterButton');

        filterItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const area = this.dataset.area;
                const filterIcon = '<i class="bi bi-funnel me-2"></i>';

                filterItems.forEach(el => el.classList.remove('active'));
                this.classList.add('active');

                if (area === 'all') {
                    filterButton.innerHTML = filterIcon + 'Filter by Area';
                } else {
                    filterButton.innerHTML = filterIcon + this.textContent.trim();
                }

                filterAreaCards(area);
                document.getElementById('areaFilterMenu').classList.remove('show');
            });
        });

        function filterAreaCards(selectedArea) {
            areaCards.forEach(card => {
                if (selectedArea === 'all') {
                    card.style.display = 'block';
                } else {
                    card.style.display = card.dataset.area === selectedArea ? 'block' : 'none';
                }
            });
        }
    }

    // Trigger change for existing selections to populate dependent fields
    $('.product-select').each(function() {
        if ($(this).val()) {
            $(this).trigger('select2:select');
        }
    });
});

// ADD MATERIAL FUNCTIONALITY
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-material') || e.target.closest('.add-material')) {
        e.preventDefault();
        const section = e.target.closest('.material-section');
        const tbody = section.querySelector('.material-rows');
        const lastRow = tbody.querySelector('.material-row:last-child');
        const clone = lastRow.cloneNode(true);
        const newIndex = Date.now();

        // Update all input names with new index
        clone.querySelectorAll('input, select').forEach(el => {
            if (el.name) {
                el.name = el.name.replace(/materials\[\d+\]/, 'materials[' + newIndex + ']');
            }
            if (el.type !== 'hidden') {
                if (el.tagName === 'INPUT' && el.type === 'number') {
                    el.value = '';
                } else if (el.tagName === 'SELECT') {
                    el.value = '';
                    // Reset any hidden options
                    Array.from(el.options).forEach(o => {
                        o.hidden = false;
                        o.selected = false;
                    });
                }
            }
        });

        // Update the data-index attribute
        clone.setAttribute('data-index', newIndex);
        tbody.appendChild(clone);

        // Initialize Select2 for the new row
        $(clone).find('.material-select').select2({
            width: '100%'
        });
    }
});

// REMOVE ROW FUNCTIONALITY
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row-btn') || e.target.closest('.remove-row-btn')) {
        e.preventDefault();
        const btn = e.target.closest('.remove-row-btn');
        const row = btn.closest('.material-row');
        const tbody = row.closest('.material-rows');

        if (tbody.children.length > 1) {
            // Destroy Select2 before removing the row
            $(row).find('.material-select').select2('destroy');
            row.remove();
        }
    }
});
</script>
