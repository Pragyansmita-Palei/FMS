window.addEventListener('pageshow', () => {
    window.dispatchEvent(new Event('resize'));
});

$(document).ready(function () {

    /* =========================
       NORMAL SELECT2 FIELDS
    ========================= */

    $('.sales-associate-select').select2({
        width: '100%'
    });

    $('.material-select').select2({
        width: '100%'
    });

    $('.brand-select').select2({
        width: '100%',
        dropdownParent: $('#addCatalogueModal')
    });


    /* =========================
       BRAND SELECT2 (ADD PRODUCT MODAL)
    ========================= */

    $('#addProductModal').on('shown.bs.modal', function () {

        // destroy if already initialized
        if ($('#brandSelect').data('select2')) {
            $('#brandSelect').select2('destroy');
        }

        $('#brandSelect').select2({
            tags: true,
            placeholder: "Select or type brand",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addProductModal'),

            createTag: function (params) {
                let term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            },

            insertTag: function (data, tag) {
                data.unshift(tag);
            }

        });

    });


    /* =========================
       ADD SELLING UNIT MODAL
    ========================= */

    $('#addSellingUnitModal select').select2({
        dropdownParent: $('#addSellingUnitModal'),
        width: '100%'
    });


    /* =========================
       PAYMENT MODE MODAL
    ========================= */

    $('#payment_mode').select2({
        dropdownParent: $('#receivedPaymentModal'),
        width: '100%'
    });


    /* =========================
       ADD UNIT MODAL
    ========================= */

    $('#addUnitModal').on('shown.bs.modal', function () {

        $(this).find('select').select2({
            dropdownParent: $('#addUnitModal'),
            width: '100%'
        });

    });


    /* =========================
       GENERIC MODAL SELECT2
    ========================= */

    $('.modal').on('shown.bs.modal', function () {

        $(this).find('select').not('#brandSelect').select2({
            dropdownParent: $(this),
            width: '100%'
        });

    });

     $('select').select2({
        // width: '100%'
    });
});
