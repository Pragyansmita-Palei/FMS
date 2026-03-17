@if($step == 2)

<div class="container mt-4">
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
                @foreach($areas ?? [] as $area)
                <a class="custom-dropdown-item" href="#" data-area="area-{{ $area['id'] }}">
                    <i class="bi bi-folder me-2"></i>{{ $area['name'] }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Add Area Button -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#areaModal">
            <i class="bi bi-plus-circle"></i> Add Area
        </button>
    </div>

    <div id="areaTables"></div>

    <div class="text-end mt-4">
        <button class="btn btn-success" id="nextStep">
            Save & Continue →
        </button>
    </div>
</div>


<!-- AREA MODAL -->
<div class="modal fade" id="areaModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="areaModalTitle">Add Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 position-relative">
                    <label class="fw-bold">Area Name</label>
                    <input type="text" id="areaSearchInput" class="form-control" placeholder="Type to search or select area" autocomplete="off">
                    <div id="areaSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; max-height: 250px; overflow-y: auto; display: none;"></div>
                </div>

                <div id="measurementSection" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">Measurements</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Reference <span class="text-danger">*</span></th>
                                    <th>Unit <span class="text-danger">*</span></th>
                                    <th>Length</th>
                                    <th>Width <span class="text-danger">*</span></th>
                                    <th>Height</th>
                                    <th>Qty <span class="text-danger">*</span></th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="measurementRows"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="addAreaMeasurementBtn">Add Area</button>
                <button type="button" class="btn btn-primary" id="updateAreaMeasurementBtn" style="display: none;">Update Area</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .area-card {
        border:1px solid #e5e7eb;
        border-radius:10px;
        margin-bottom: 20px;
        transition: all 0.2s ease;
    }
    .area-header {
        padding:14px 18px;
        border-bottom:1px solid #eee;
        font-weight:600;
        font-size:18px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0;
    }
    .measurement-table th {
        font-size:14px;
        color:#6b7280;
    }
    .measurement-table td input,
    .measurement-table td select {
        min-width:110px;
    }
    .is-invalid {
        border:1px solid #dc3545 !important;
        background:#fff5f5;
    }
    .position-relative {
        position: relative;
    }
    #areaSuggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        display: none;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background: white;
    }
    .list-group-item {
        cursor: pointer;
        padding: 10px 15px;
        border: none;
        border-bottom: 1px solid #f0f0f0;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .list-group-item.create-new {
        color: #198754;
        font-weight: 500;
    }
    .list-group-item.create-new:hover {
        background-color: #d1e7dd;
    }
    .list-group-item.text-muted:hover {
        background-color: transparent;
        cursor: default;
    }
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        color: #6c757d !important;
    }
    .action-buttons .btn:hover {
        color: #5a6268 !important;
    }
    .list-group-item.disabled {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        pointer-events: none;
        opacity: 0.8;
        cursor: not-allowed;
    }
    .list-group-item.disabled:hover {
        background-color: #f8f9fa !important;
    }
    .badge.bg-secondary {
        font-size: 0.7rem;
        padding: 0.35em 0.65em;
        margin-left: 5px;
    }
    .edit-area-btn, .remove-area-btn {
        color: #6c757d !important;
        padding: 0.25rem 0.5rem;
        border: none;
        background: transparent;
    }
    .edit-area-btn:hover, .remove-area-btn:hover {
        color: #5a6268 !important;
    }
    .edit-area-btn i, .remove-area-btn i {
        color: #6c757d;
    }
    .edit-area-btn:hover i, .remove-area-btn:hover i {
        color: #5a6268;
    }
    .area-header .btn {
        color: #6c757d !important;
    }
    .area-header .btn:hover {
        color: #5a6268 !important;
    }
    .area-header .btn i {
        color: #6c757d;
    }
    .area-header .btn:hover i {
        color: #5a6268;
    }

    /* Empty state styling */
    .empty-state-container {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 16px;
        padding: 60px 40px;
        margin: 20px 0;
        text-align: center;
        min-height: 350px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon {
        font-size: 64px;
        color: #adb5bd;
        margin-bottom: 20px;
    }

    .empty-state-title {
        font-size: 24px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 12px;
    }

    .empty-state-text {
        font-size: 16px;
        color: #6c757d;
        margin-bottom: 24px;
    }

    .empty-state-btn {
        padding: 10px 24px;
        font-size: 16px;
        border-radius: 8px;
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

    .gap-2 {
        gap: 0.5rem;
    }
</style>

<script>
$(document).ready(function() {

    // Initialize variables
    window.selectedAreaId = null;
    window.allAreas = @json($areas ?? []);
    window.savedMeasurements = @json($savedMeasurements ?? []);
    window.areaMeasurements = {};
    window.projectId = "{{ $projectId }}";
    window.deletedRows = []; // Track deleted row IDs
    window.deletedAreas = []; // Track deleted area IDs
    window.editMode = false; // Track if we're in edit mode
    window.editingAreaId = null; // Track which area is being edited

    // Load saved data
    if (window.savedMeasurements && Object.keys(window.savedMeasurements).length > 0) {
        Object.keys(window.savedMeasurements).forEach(areaId => {
            let area = window.savedMeasurements[areaId];
            window.areaMeasurements[areaId] = {
                id: areaId,
                name: area.name,
                rows: area.rows || []
            };
        });
    }

    // ALWAYS call renderAreas() - this will show either the saved areas
    // or the "No measurements added yet" message
    renderAreas();

    // Reset modal when closed
    $('#areaModal').on('hidden.bs.modal', function () {
        resetModal();
    });

    // Add measurement row function
    function addMeasurementRow(rowData = null) {
        let rowId = rowData?.id || '';
        let row = `
        <tr data-row-id="${rowId}">
            <td><input type="text" class="form-control form-control-sm ref" placeholder="e.g., Wall A" value="${rowData?.ref || ''}"></td>
            <td>
                <select class="form-select form-select-sm unit">
                    <option value="CM" ${rowData?.unit == 'CM' ? 'selected' : ''}>CM</option>
                    <option value="INCH" ${rowData?.unit == 'INCH' ? 'selected' : ''}>INCH</option>
                    <option value="FT" ${rowData?.unit == 'FT' ? 'selected' : ''}>FT</option>
                </select>
            </td>
            <td><input type="number" step="0.01" class="form-control form-control-sm length" placeholder="Length" value="${rowData?.length || ''}"></td>
            <td><input type="number" step="0.01" class="form-control form-control-sm width" placeholder="Width" value="${rowData?.width || ''}"></td>
            <td><input type="number" step="0.01" class="form-control form-control-sm height" placeholder="Height" value="${rowData?.height || ''}"></td>
            <td><input type="number" step="1" min="1" class="form-control form-control-sm qty" value="${rowData?.qty || '1'}"></td>
            <td><input type="text" class="form-control form-control-sm remark" placeholder="Optional" value="${rowData?.remark || ''}"></td>
            <td class="text-center">
                <div class="action-buttons">
                    <button type="button" class="btn btn-sm addRow" title="Add Row">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button type="button" class="btn btn-sm removeRow" title="Remove Row">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;
        $('#measurementRows').append(row);
    }

    // Show suggestions dropdown
    function showSuggestions() {
        $('#areaSuggestions').show();
    }

    // Hide suggestions dropdown
    function hideSuggestions() {
        setTimeout(function() {
            $('#areaSuggestions').hide();
        }, 200);
    }

    // Area search with debounce
    let searchTimeout;
    $('#areaSearchInput').on('input focus', function() {
        // Don't show suggestions in edit mode
        if (window.editMode) {
            return;
        }

        clearTimeout(searchTimeout);
        let value = $(this).val().toLowerCase().trim();

        showSuggestions();

        searchTimeout = setTimeout(function() {
            let box = $('#areaSuggestions');
            box.empty();

            if (value.length > 0) {
                // Filter areas
                let matches = window.allAreas.filter(a =>
                    a.name.toLowerCase().includes(value)
                );

                if (matches.length > 0) {
                    // Show matching areas
                    matches.forEach(area => {
                        // Check if area is already added
                        let isAdded = window.areaMeasurements[area.id] ? true : false;

                        if (isAdded) {
                            box.append(`
                                <a href="#" class="list-group-item list-group-item-action disabled text-muted"
                                   style="cursor: not-allowed; background-color: #f8f9fa;"
                                   onclick="return false;">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    ${area.name}
                                </a>
                            `);
                        } else {
                            box.append(`
                                <a href="#" class="list-group-item list-group-item-action select-area"
                                   data-id="${area.id}"
                                   data-name="${area.name}">
                                    ${area.name}
                                </a>
                            `);
                        }
                    });

                    // Add option to create new
                    box.append(`
                        <a href="#" class="list-group-item list-group-item-action create-new"
                           data-name="${value}">
                            <i class="bi bi-plus-circle"></i> Create new area: "${value}"
                        </a>
                    `);
                } else {
                    // No matches - show create option only
                    box.append(`
                        <a href="#" class="list-group-item list-group-item-action create-new"
                           data-name="${value}">
                            <i class="bi bi-plus-circle"></i> Create new area: "${value}"
                        </a>
                    `);
                }
            } else {
                // Empty search - show recent areas
                if (window.allAreas.length > 0) {
                    box.append('<div class="list-group-item text-muted"><i class="bi bi-clock-history me-2"></i>Recent areas:</div>');

                    // Show last 5 areas
                    window.allAreas.slice(0, 5).forEach(area => {
                        let isAdded = window.areaMeasurements[area.id] ? true : false;

                        if (isAdded) {
                            box.append(`
                                <a href="#" class="list-group-item list-group-item-action disabled text-muted"
                                   style="cursor: not-allowed; background-color: #f8f9fa;"
                                   onclick="return false;">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    ${area.name}
                                </a>
                            `);
                        } else {
                            box.append(`
                                <a href="#" class="list-group-item list-group-item-action select-area"
                                   data-id="${area.id}"
                                   data-name="${area.name}">
                                    ${area.name}
                                </a>
                            `);
                        }
                    });
                } else {
                    box.append('<div class="list-group-item text-muted"><i class="bi bi-info-circle me-2"></i>No areas found. Type to create new.</div>');
                }
            }
        }, 300);
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#areaSearchInput, #areaSuggestions').length) {
            $('#areaSuggestions').hide();
        }
    });

    // Select area from suggestions
    $(document).on('click', '.select-area', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let areaId = $(this).data('id');
        let areaName = $(this).data('name');

        // Double-check if area is already added
        if (window.areaMeasurements[areaId]) {
            alert(`"${areaName}" has already been added. Please select a different area.`);
            $('#areaSearchInput').val('').focus();
            $('#areaSearchInput').trigger('input');
            return;
        }

        window.selectedAreaId = areaId;
        $('#areaSearchInput').val(areaName);
        hideSuggestions();

        $('#measurementSection').show();
        $('#measurementRows').empty();
        addMeasurementRow();
    });

    // Create new area
    $(document).on('click', '.create-new', function(e) {
        e.preventDefault();

        let name = $(this).data('name');

        // Check if area with this name already exists
        let existingArea = window.allAreas.find(a =>
            a.name.toLowerCase() === name.toLowerCase()
        );

        if (existingArea) {
            // Check if it's already added
            if (window.areaMeasurements[existingArea.id]) {
                alert(`"${existingArea.name}" already exists and has been added. Please select it from the list.`);
                $('#areaSearchInput').val(existingArea.name).focus();
                $('#areaSearchInput').trigger('input');
                return;
            } else {
                // Area exists but not added yet - select it automatically
                window.selectedAreaId = existingArea.id;
                $('#areaSearchInput').val(existingArea.name);
                hideSuggestions();
                $('#measurementSection').show();
                $('#measurementRows').empty();
                addMeasurementRow();
                return;
            }
        }

        // Show loading state
        let $btn = $(this);
        let originalText = $btn.html();
        $btn.html('<i class="bi bi-hourglass-split"></i> Creating...');

        $.ajax({
            url: "{{ route('areas.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                name: name
            },
            success: function(res) {
                window.selectedAreaId = res.id;

                // Add to allAreas
                window.allAreas.push(res);

                $('#areaSearchInput').val(res.name);
                hideSuggestions();

                $('#measurementSection').show();
                $('#measurementRows').empty();
                addMeasurementRow();

                // Update filter dropdown with new area
                updateFilterDropdown();
            },
            error: function(xhr) {
                alert("Error creating area. Please try again.");
                $btn.html(originalText);
            }
        });
    });

    // Add row in modal (plus icon)
    $(document).on('click', '.addRow', function() {
        addMeasurementRow();
    });

    // Remove row in modal (trash icon)
    $(document).on('click', '.removeRow', function() {
        let row = $(this).closest('tr');
        let rowId = row.data('row-id');

        if ($('#measurementRows tr').length > 1) {
            // If row has an ID, add to deleted rows
            if (rowId) {
                window.deletedRows.push(rowId);
            }
            row.remove();
        } else {
            if (confirm("This is the last row. Removing it will clear all measurements. Continue?")) {
                if (rowId) {
                    window.deletedRows.push(rowId);
                }
                $('#measurementRows').empty();
            }
        }
    });

    // Add area button click
    $('#addAreaMeasurementBtn').click(function() {
        if (!window.selectedAreaId) {
            alert("Please select or create an area first.");
            return;
        }

        // Double-check if area is already added (prevent double submission)
        if (window.areaMeasurements[window.selectedAreaId]) {
            alert("This area has already been added. Please select a different area.");
            resetModal();
            $('#areaModal').modal('hide');
            return;
        }

        // Validate rows
        let isValid = true;
        let rows = [];

        $('#measurementRows tr').each(function() {
            let ref = $(this).find('.ref').val();
            let width = $(this).find('.width').val();
            let qty = $(this).find('.qty').val();

            // Remove existing invalid class
            $(this).find('.ref, .width, .qty').removeClass('is-invalid');

            if (!ref || ref.trim() === '') {
                $(this).find('.ref').addClass('is-invalid');
                isValid = false;
            }
            if (!width || width.trim() === '' || parseFloat(width) <= 0) {
                $(this).find('.width').addClass('is-invalid');
                isValid = false;
            }
            if (!qty || qty.trim() === '' || parseInt(qty) <= 0) {
                $(this).find('.qty').addClass('is-invalid');
                isValid = false;
            }

            rows.push({
                ref: ref || '',
                unit: $(this).find('.unit').val() || 'CM',
                length: $(this).find('.length').val() || '',
                width: width || '',
                height: $(this).find('.height').val() || '',
                qty: qty || '1',
                remark: $(this).find('.remark').val() || ''
            });
        });

        if (!isValid) {
            alert("Please fill in all required fields (*)");
            return;
        }

        // Add to areaMeasurements
        window.areaMeasurements[window.selectedAreaId] = {
            id: window.selectedAreaId,
            name: $('#areaSearchInput').val(),
            rows: rows
        };

        // Render areas
        renderAreas();

        // Update filter dropdown with new area
        updateFilterDropdown();

        // Reset and close modal
        resetModal();
        $('#areaModal').modal('hide');
    });

    // Update area button click - UPDATES AREA NAME IN DATABASE IMMEDIATELY
    $('#updateAreaMeasurementBtn').click(function() {
        if (!window.selectedAreaId || !window.editingAreaId) {
            alert("Error: No area selected for update.");
            return;
        }

        let newAreaName = $('#areaSearchInput').val();
        let areaId = window.selectedAreaId;

        if (!newAreaName || newAreaName.trim() === '') {
            alert("Area name cannot be empty.");
            return;
        }

        // Show loading state
        let $btn = $(this);
        let originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');

        // Make AJAX call to update area name in database
        $.ajax({
            url: "{{ route('areas.update') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: areaId,
                name: newAreaName
            },
            success: function(response) {
                // Get the existing area data
                let existingArea = window.areaMeasurements[areaId];

                // Update the area name in allAreas array
                let areaIndex = window.allAreas.findIndex(a => a.id == areaId);
                if (areaIndex !== -1) {
                    window.allAreas[areaIndex].name = newAreaName;
                }

                // Update ONLY the area name in local object
                window.areaMeasurements[areaId] = {
                    id: areaId,
                    name: newAreaName,
                    rows: existingArea.rows // Keep existing rows unchanged
                };

                // Render areas to show updated name
                renderAreas();

                // Update filter dropdown
                updateFilterDropdown();

                // Reset edit mode and close modal
                window.editMode = false;
                window.editingAreaId = null;
                resetModal();
                $('#areaModal').modal('hide');

                // Show success message
                alert("Area name updated successfully!");
            },
            error: function(xhr) {
                let errorMessage = "Error updating area name. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
                console.error(xhr.responseText);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Edit area function - ONLY SHOWS AREA NAME FOR EDITING
    function editArea(areaId) {
        let area = window.areaMeasurements[areaId];
        if (!area) {
            alert("Area not found!");
            return;
        }

        console.log("Editing area name:", area.name); // Debug log

        // Set edit mode
        window.editMode = true;
        window.editingAreaId = areaId;
        window.selectedAreaId = areaId;

        // Update modal title and buttons
        $('#areaModalTitle').text('Edit Area');
        $('#addAreaMeasurementBtn').hide();
        $('#updateAreaMeasurementBtn').show();

        // Populate ONLY the area name field
        $('#areaSearchInput').val(area.name);
        $('#areaSearchInput').prop('readonly', false); // Make area name editable

        // Hide suggestions dropdown
        $('#areaSuggestions').hide();

        // KEEP MEASUREMENT SECTION HIDDEN - we're only editing the area name
        $('#measurementSection').hide();

        // Clear any rows just to be safe
        $('#measurementRows').empty();

        // Show modal
        $('#areaModal').modal('show');
    }

    // Reset modal function
    function resetModal() {
        window.selectedAreaId = null;
        window.editMode = false;
        window.editingAreaId = null;

        // Reset modal title and buttons
        $('#areaModalTitle').text('Add Area');
        $('#addAreaMeasurementBtn').show();
        $('#updateAreaMeasurementBtn').hide();

        $('#areaSearchInput').val('');
        $('#areaSearchInput').prop('readonly', false);
        $('#areaSuggestions').empty().hide();
        $('#measurementSection').hide();
        $('#measurementRows').empty();
    }

    // Render areas function
    function renderAreas() {
        let container = $('#areaTables');
        container.empty();

        if (Object.keys(window.areaMeasurements).length === 0) {
            container.append(`
                <div class="empty-state-container">
                    <div class="empty-state-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="empty-state-title">
                        No areas added yet
                    </div>
                    <div class="empty-state-text">
                        Click the "Add Area" button to get started with your measurements.
                    </div>
                    <button class="btn btn-success empty-state-btn" data-bs-toggle="modal" data-bs-target="#areaModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Area
                    </button>
                </div>
            `);
            return;
        }

        Object.values(window.areaMeasurements).forEach(area => {
            let rows = '';

            if (area.rows && area.rows.length > 0) {
                area.rows.forEach((r) => {
                    rows += `
                    <tr data-row-id="${r.id || ''}">
                        <td><input type="text" class="form-control ref" value="${r.ref || ''}"></td>
                        <td>
                            <select class="form-select unit">
                                <option value="CM" ${r.unit == 'CM' ? 'selected' : ''}>CM</option>
                                <option value="INCH" ${r.unit == 'INCH' ? 'selected' : ''}>INCH</option>
                                <option value="FT" ${r.unit == 'FT' ? 'selected' : ''}>FT</option>
                            </select>
                        </td>
                        <td><input type="number" step="0.01" class="form-control length" value="${r.length || ''}"></td>
                        <td><input type="number" step="0.01" class="form-control width" value="${r.width || ''}"></td>
                        <td><input type="number" step="0.01" class="form-control height" value="${r.height || ''}"></td>
                        <td><input type="number" step="1" min="1" class="form-control qty" value="${r.qty || 1}"></td>
                        <td><input type="text" class="form-control remark" value="${r.remark || ''}"></td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <button type="button" class="btn btn-sm addRowArea" title="Add Row">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                                <button type="button" class="btn btn-sm removeRowArea" title="Remove Row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
            }

            let areaCard = `
            <div class="card area-card mb-4" data-area-id="${area.id}" data-area-name="${area.name.toLowerCase().replace(/\s+/g, '-')}">
                <div class="area-header">
                    <span><i class="bi bi-building me-2"></i>${area.name}</span>
                    <div>
                        <button type="button" class="btn btn-sm edit-area-btn" title="Edit Area">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn btn-sm remove-area-btn" title="Delete Area">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table measurement-table">
                            <thead>
                                <tr>
                                    <th>Reference <span class="text-danger">*</span></th>
                                    <th>Unit <span class="text-danger">*</span></th>
                                    <th>Length</th>
                                    <th>Width <span class="text-danger">*</span></th>
                                    <th>Height</th>
                                    <th>Qty <span class="text-danger">*</span></th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody data-area="${area.id}">
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>`;

            container.append(areaCard);
        });

        // Re-initialize filter after rendering
        initializeFilter();
    }

    // Update filter dropdown with current areas
    function updateFilterDropdown() {
        let filterMenu = $('#areaFilterMenu');
        filterMenu.empty();

        // Add All Areas option
        filterMenu.append(`
            <a class="custom-dropdown-item active" href="#" data-area="all">
                <i class="bi bi-grid me-2"></i>All Areas
            </a>
            <div class="dropdown-divider"></div>
        `);

        // Add each area from areaMeasurements
        Object.values(window.areaMeasurements).forEach(area => {
            filterMenu.append(`
                <a class="custom-dropdown-item" href="#" data-area="${area.name.toLowerCase().replace(/\s+/g, '-')}">
                    <i class="bi bi-folder me-2"></i>${area.name}
                </a>
            `);
        });

        // Re-initialize filter event listeners
        initializeFilter();
    }

    // Edit area click handler
    $(document).on('click', '.edit-area-btn', function() {
        let areaCard = $(this).closest('.area-card');
        let areaId = areaCard.data('area-id');
        editArea(areaId);
    });

    // Add row inside area table (plus icon)
    $(document).on('click', '.addRowArea', function() {
        let tbody = $(this).closest('tbody');
        let areaId = tbody.data('area');

        let newRow = `
        <tr>
            <td><input type="text" class="form-control ref" placeholder="e.g., Wall A"></td>
            <td>
                <select class="form-select unit">
                    <option value="CM">CM</option>
                    <option value="INCH">INCH</option>
                    <option value="FT">FT</option>
                </select>
            </td>
            <td><input type="number" step="0.01" class="form-control length" placeholder="Length"></td>
            <td><input type="number" step="0.01" class="form-control width" placeholder="Width"></td>
            <td><input type="number" step="0.01" class="form-control height" placeholder="Height"></td>
            <td><input type="number" step="1" min="1" class="form-control qty" value="1"></td>
            <td><input type="text" class="form-control remark" placeholder="Optional"></td>
            <td class="text-center">
                <div class="action-buttons">
                    <button type="button" class="btn btn-sm addRowArea" data-area="${areaId}" title="Add Row">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button type="button" class="btn btn-sm removeRowArea" title="Remove Row">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>`;

        tbody.append(newRow);
    });

    // Remove row inside area table (trash icon)
    $(document).on('click', '.removeRowArea', function() {
        let row = $(this).closest('tr');
        let rowId = row.data('row-id');
        let tbody = $(this).closest('tbody');
        let areaId = tbody.data('area');

        if (tbody.find('tr').length > 1) {
            // If row has an ID, add to deleted rows
            if (rowId) {
                window.deletedRows.push(rowId);
            }
            row.remove();
        } else {
            if (confirm("This is the last row. Deleting it will remove the entire area. Continue?")) {
                deleteArea(areaId);
            }
        }
    });

    // Remove entire area with DB delete
    $(document).on('click', '.remove-area-btn', function() {
        let areaCard = $(this).closest('.area-card');
        let areaId = areaCard.data('area-id');
        deleteArea(areaId);
    });

    // Function to delete area
    function deleteArea(areaId) {
        if (confirm("Are you sure you want to delete this entire area and all its measurements? This action cannot be undone.")) {
            // Check if area has an ID (saved in DB)
            if (areaId && !isNaN(areaId)) {
                // Show loading state on the delete button
                let $btn = $(`.area-card[data-area-id="${areaId}"] .remove-area-btn`);
                $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

                // Delete from database
                $.ajax({
                    url: "{{ route('measurements.delete-area') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        area_id: areaId,
                        project_id: window.projectId
                    },
                    success: function(response) {
                        // Remove from local object
                        delete window.areaMeasurements[areaId];

                        // Remove from allAreas array
                        window.allAreas = window.allAreas.filter(a => a.id != areaId);

                        // Remove from UI
                        $(`.area-card[data-area-id="${areaId}"]`).remove();

                        // Update filter dropdown
                        updateFilterDropdown();

                        // Show info if no areas left
                        if (Object.keys(window.areaMeasurements).length === 0) {
                            renderAreas();
                        }
                    },
                    error: function(xhr) {
                        alert("Error deleting area. Please try again.");
                        $btn.prop('disabled', false).html('<i class="bi bi-trash"></i>');
                    }
                });
            } else {
                // New area without ID - just remove from UI and local object
                delete window.areaMeasurements[areaId];
                $(`.area-card[data-area-id="${areaId}"]`).remove();

                // Update filter dropdown
                updateFilterDropdown();

                // Show info if no areas left
                if (Object.keys(window.areaMeasurements).length === 0) {
                    renderAreas();
                }
            }
        }
    }

    // Save & Continue
    $('#nextStep').click(function() {
        let hasError = false;

        // Remove existing invalid class
        $('.ref, .width, .qty').removeClass('is-invalid');

        // Validate each row
        $('#areaTables tbody tr').each(function() {
            let ref = $(this).find('.ref');
            let width = $(this).find('.width');
            let qty = $(this).find('.qty');

            if (!ref.val() || ref.val().trim() === '') {
                ref.addClass('is-invalid');
                hasError = true;
            }

            if (!width.val() || width.val().trim() === '' || parseFloat(width.val()) <= 0) {
                width.addClass('is-invalid');
                hasError = true;
            }

            if (!qty.val() || qty.val().trim() === '' || parseInt(qty.val()) <= 0) {
                qty.addClass('is-invalid');
                hasError = true;
            }
        });

        if (hasError) {
            alert("Please fill in all required fields (*) with valid values.");
            return;
        }

        if (Object.keys(window.areaMeasurements).length === 0) {
            alert("Please add at least one area before continuing.");
            return;
        }

        // Sync table data with areaMeasurements
        $('#areaTables .area-card').each(function() {
            let areaId = $(this).data('area-id');
            let rows = [];

            $(this).find('tbody tr').each(function() {
                let rowId = $(this).data('row-id');
                rows.push({
                    id: rowId || null,
                    ref: $(this).find('.ref').val() || '',
                    unit: $(this).find('.unit').val() || 'CM',
                    length: $(this).find('.length').val() || '',
                    width: $(this).find('.width').val() || '',
                    height: $(this).find('.height').val() || '',
                    qty: $(this).find('.qty').val() || '1',
                    remark: $(this).find('.remark').val() || ''
                });
            });

            if (window.areaMeasurements[areaId]) {
                window.areaMeasurements[areaId].rows = rows;
            }
        });

        // Disable button to prevent double submission
        let $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        // Save to DB
        $.ajax({
            url: "{{ route('projects.store.step2') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                project_id: window.projectId,
                measurements: window.areaMeasurements,
                deleted_rows: window.deletedRows,
                deleted_areas: window.deletedAreas
            },
            success: function(response) {
                window.location.href = "{{ route('projects.create', ['step' => 3, 'project_id' => $projectId]) }}";
            },
            error: function(xhr, status, error) {
                console.error("Error saving data:", error);
                alert("An error occurred while saving. Please try again.");
                $btn.prop('disabled', false).text('Save & Continue →');
            }
        });
    });

    // Handle empty state button click
    $(document).on('click', '.empty-state-btn', function() {
        $('#areaModal').modal('show');
    });

    // Initialize filter functionality
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
                    card.style.display = card.dataset.areaName === selectedArea ? 'block' : 'none';
                }
            });
        }
    }

    // Custom Dropdown Toggle
    const dropdownButton = document.getElementById('areaFilterButton');
    const dropdownMenu = document.getElementById('areaFilterMenu');

    if (dropdownButton && dropdownMenu) {
        dropdownButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        $(document).on('click', function(e) {
            if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Initialize filter on page load
    initializeFilter();

    // Update filter dropdown with initial areas
    updateFilterDropdown();

});
</script>

@endif
