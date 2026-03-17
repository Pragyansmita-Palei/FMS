$(document).ready(function () {

    // keep original options
    let allAddonOptions = $('#addon_products option').clone();

    function initAddon() {
        $('#addon_products').select2({
            placeholder: "Select Addon Products",
            width: '100%'
        });
    }

    initAddon();

    $('#main_product').on('change', function () {

        let mainId   = $(this).val();
        let mainType = $('#main_product option:selected').data('group-type');

        // clear and rebuild addon options
        $('#addon_products').empty();

        if (!mainType) {
            initAddon();
            return;
        }

        allAddonOptions.each(function () {

            let addonType = $(this).data('group-type');
            let addonId   = $(this).val();

            // skip same item
            if (addonId == mainId) {
                return;
            }

            // ONLY same group type
            if (addonType == mainType) {
                $('#addon_products').append($(this).clone());
            }

        });

        // rebuild select2
        $('#addon_products').select2('destroy');
        initAddon();

    });

});
