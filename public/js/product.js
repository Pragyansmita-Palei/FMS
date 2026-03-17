$(document).ready(function () {

    /* =========================
   STORE FILTER DROPDOWN
========================= */

$(document).on('click','.storeFilterItem',function(){

    let storeId = $(this).data('id');

    $('#store_id').val(storeId);

    $('#storeFilterForm').submit();

});
/* =========================
   PER PAGE DROPDOWN
========================= */

$(document).on('click','.perPageItem',function(){

    let value = $(this).data('value');

    $('#per_page_value').val(value);

    $('#perPageForm').submit();

});


    /* =========================
       VIEW MODAL
    ========================= */
    $(document).on('click', '.viewProductBtn', function () {

        let id = $(this).data('id');

        $.get('/products/' + id + '/view', function (res) {

            $('#v_item_code').val(res.item_code);
            $('#v_store').val(res.store_name);
            $('#v_branch').val(res.branch_name);
            $('#v_brand').val(res.brand_name);
            $('#v_name').val(res.name);
            $('#v_quantity').val(res.quantity);
            $('#v_description').val(res.description);
            $('#v_group').val(res.group_type_name);
            $('#v_unit').val(res.selling_unit_name);
            $('#v_mrp').val(res.mrp);
            $('#v_tax').val(res.tax_rate);
            $('#v_discount').val(res.discount);
            $('#v_total').val(res.total_price);

            new bootstrap.Modal(
                document.getElementById('viewProductModal')
            ).show();
        });
    });


    /* =========================
       SELECT2 (ADD)
    ========================= */
    $('#addProductModal').on('shown.bs.modal', function () {

        if ($('#brandSelect').hasClass('select2-hidden-accessible')) return;

        $('#brandSelect').select2({
            tags: true,
            placeholder: "Select or type brand",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addProductModal')
        });

    });


    /* =========================
       AUTO ITEM CODE
    ========================= */
    let next = $('#itemCode').data('next-code');

    if (next !== undefined) {
        $('#itemCode').val('FMS-I-' + next);
    }


    /* =========================
       GROUP CHANGE (ADD)
    ========================= */
    $('#groupType').on('change', function () {

        let groupId = $(this).val();

        $('#sellingUnit').html('<option value="">Select Unit</option>');
        $('#customUnitBox').addClass('d-none');
        $('#customUnitInput').val('').prop('required', false);

        if (groupId === '__other__') {

            $('#customGroupBox').removeClass('d-none');
            $('#customGroupInput').prop('required', true);

            $('#sellingUnit').html(`
                <option value="">Select Unit</option>
                <option value="__other__">Other</option>
            `);

            $('#sellingUnit').val('__other__').trigger('change');
            return;
        }

        $('#customGroupBox').addClass('d-none');
        $('#customGroupInput').val('').prop('required', false);

        if (!groupId) return;

        loadUnitsByGroupId(groupId);
    });


    function loadUnitsByGroupId(groupId) {

        $('#sellingUnit').html('<option>Loading...</option>');

        $.get('/get-units-by-id/' + groupId, function (data) {

            let html = '<option value="">Select Unit</option>';

            data.forEach(function (u) {

                let units = u.unit_name.split(',');

                units.forEach(function (single) {

                    let name = single.trim();

                    if (name !== '') {
                        html += `<option value="${name}">${name}</option>`;
                    }

                });

            });

            html += '<option value="__other__">Other</option>';

            $('#sellingUnit').html(html);
        });
    }


    /* =========================
       UNIT CHANGE (ADD)
    ========================= */
    $(document).on('change', '#sellingUnit', function () {

        if ($(this).val() === '__other__') {

            $('#customUnitBox').removeClass('d-none');
            $('#customUnitInput').prop('required', true);

        } else {

            $('#customUnitBox').addClass('d-none');
            $('#customUnitInput').val('').prop('required', false);
        }
    });


    /* =========================
       SAFETY CHECK (ADD ONLY)
    ========================= */
    $('#addProductModal form').on('submit', function () {

        if ($('#groupType').val() === '__other__') {

            if (!$('#customGroupInput').val().trim()) {
                alert('Please enter new group type');
                return false;
            }
        }

        if ($('#sellingUnit').val() === '__other__') {

            if (!$('#customUnitInput').val().trim()) {
                alert('Please enter new selling unit');
                return false;
            }
        }

        return true;
    });


    /* =========================
       TOTAL (ADD)
    ========================= */
    function calculateAddTotal() {

        let mrp      = parseFloat($('#mrp').val()) || 0;
        let qty      = parseFloat($('#quantity').val()) || 0;
        let taxRate  = parseFloat($('#tax_rate').val()) || 0;
        let discount = parseFloat($('#discount').val()) || 0;

        let base = mrp * qty;

        let total =
            base +
            (base * taxRate / 100) -
            (base * discount / 100);

        $('#total').val(total.toFixed(2));
    }

    $(document).on(
        'keyup change',
        '#mrp,#quantity,#tax_rate,#discount',
        calculateAddTotal
    );


    /* =========================
       STORE → BRANCH (ADD)
    ========================= */
    let selectedStore  = $('#storeSelect').val();
    let selectedBranch = $('#branchSelect').data('selected');

    if (selectedStore) {
        loadAddBranches(selectedStore, selectedBranch);
    }

    $('#storeSelect').on('change', function () {
        loadAddBranches($(this).val(), null);
    });

    function loadAddBranches(storeId, selectedBranchId) {

        if (!storeId) {
            $('#branchSelect').html('<option value="">Select Branch</option>');
            return;
        }

        $.get('/stores/' + storeId + '/branches', function (data) {

            let html = '<option value="">Select Branch</option>';

            data.forEach(function (branch) {

                let selected =
                    (selectedBranchId == branch.id) ? 'selected' : '';

                html += `<option value="${branch.id}" ${selected}>
                            ${branch.branch_name}
                         </option>`;
            });

            $('#branchSelect').html(html);
        });
    }



    /* ======================================================
       ===================== EDIT MODAL =====================
    ====================================================== */

    $(document).on('click', '.editProductBtn', function () {

        let id = $(this).data('id');

        $.get('/products/' + id + '/edit', function (res) {

            $('#editProductForm').attr('action', '/products/' + id);

            $('#e_id').val(res.id);
            $('#e_item_code').val(res.item_code);

            $('#e_store').val(res.store_id);
            $('#e_brand').val(res.brand_id);

            $('#e_name').val(res.name);
            $('#e_quantity').val(res.quantity);
            $('#e_design').val(res.design_number);
            $('#e_description').val(res.description);

            $('#e_group').val(res.group_type_id);

            $('#e_mrp').val(res.mrp);
            $('#e_tax').val(res.tax_rate);
            $('#e_discount').val(res.discount);

            loadEditBranches(res.store_id, res.branch_id);
            loadEditUnits(res.group_type_id, res.selling_unit);

            calculateEditTotal();

            new bootstrap.Modal(
                document.getElementById('editProductModal')
            ).show();

        });

    });


    /* =========================
       STORE → BRANCH (EDIT)
    ========================= */
    $('#e_store').on('change', function () {
        loadEditBranches($(this).val(), null);
    });

    function loadEditBranches(storeId, selectedBranch) {

        if (!storeId) {
            $('#e_branch').html('<option value="">Select Branch</option>');
            return;
        }

        $.get('/stores/' + storeId + '/branches', function (data) {

            let html = '<option value="">Select Branch</option>';

            data.forEach(function (b) {

                let sel = (selectedBranch == b.id) ? 'selected' : '';

                html += `<option value="${b.id}" ${sel}>
                            ${b.branch_name}
                         </option>`;
            });

            $('#e_branch').html(html);
        });
    }


    /* =========================
       GROUP CHANGE (EDIT)
    ========================= */
    $('#e_group').on('change', function () {

        let groupId = $(this).val();

        $('#e_customGroupBox').addClass('d-none');
        $('#e_customGroupInput').val('').prop('required', false);

        $('#e_customUnitBox').addClass('d-none');
        $('#e_customUnitInput').val('').prop('required', false);

        if (groupId === '__other__') {

            $('#e_customGroupBox').removeClass('d-none');
            $('#e_customGroupInput').prop('required', true);

            $('#e_unit').html(`
                <option value="">Select Unit</option>
                <option value="__other__">Other</option>
            `);

            return;
        }

        if (!groupId) return;

        loadEditUnits(groupId, null);
    });


    function loadEditUnits(groupId, selectedUnit) {

        $('#e_unit').html('<option>Loading...</option>');

        $.get('/get-units-by-id/' + groupId, function (data) {

            let html = '<option value="">Select Unit</option>';

            data.forEach(function (u) {

                let units = u.unit_name.split(',');

                units.forEach(function (single) {

                    let name = single.trim();

                    if (name !== '') {

                        let sel =
                            (selectedUnit == name) ? 'selected' : '';

                        html += `<option value="${name}" ${sel}>
                                    ${name}
                                 </option>`;
                    }

                });

            });

            html += '<option value="__other__">Other</option>';

            $('#e_unit').html(html);
        });
    }


    /* =========================
       UNIT CHANGE (EDIT)
    ========================= */
    $(document).on('change', '#e_unit', function () {

        if ($(this).val() === '__other__') {

            $('#e_customUnitBox').removeClass('d-none');
            $('#e_customUnitInput').prop('required', true);

        } else {

            $('#e_customUnitBox').addClass('d-none');
            $('#e_customUnitInput').val('').prop('required', false);
        }
    });


    /* =========================
       TOTAL (EDIT)
    ========================= */
    function calculateEditTotal() {

        let mrp      = parseFloat($('#e_mrp').val()) || 0;
        let qty      = parseFloat($('#e_quantity').val()) || 0;
        let taxRate  = parseFloat($('#e_tax').val()) || 0;
        let discount = parseFloat($('#e_discount').val()) || 0;

        let base = mrp * qty;

        let total =
            base +
            (base * taxRate / 100) -
            (base * discount / 100);

        $('#e_total').val(total.toFixed(2));
    }

    $(document).on(
        'keyup change',
        '#e_mrp,#e_quantity,#e_tax,#e_discount',
        calculateEditTotal
    );

});
