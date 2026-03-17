$(document).ready(function(){

    let areaMeasurements = window.savedMeasurements || {};

    function validateMeasurements() {
        let isValid = true;
        $('.is-invalid').removeClass('is-invalid');

        Object.keys(areaMeasurements).forEach(areaId => {
            areaMeasurements[areaId].rows.forEach((row, index) => {
                let tr = $(`tr[data-area="${areaId}"][data-index="${index}"]`);

                let ref    = tr.find('.ref');
                let unit   = tr.find('.unit');
                let width  = tr.find('.width');
                let height = tr.find('.height');
                let qty    = tr.find('.qty');

                if (!ref.val()) {
                    ref.addClass('is-invalid');
                    isValid = false;
                }

                if (!unit.val()) {
                    unit.addClass('is-invalid');
                    isValid = false;
                }

                if (!width.val() || width.val() <= 0) {
                    width.addClass('is-invalid');
                    isValid = false;
                }

                if (height.val() && height.val() <= 0) {
                    height.addClass('is-invalid');
                    isValid = false;
                }

                if (!qty.val() || qty.val() <= 0) {
                    qty.addClass('is-invalid');
                    isValid = false;
                }
            });
        });

        return isValid;
    }

    /* -------------------------
       LOAD SAVED MEASUREMENTS
    ------------------------- */
    if(Object.keys(areaMeasurements).length){
        Object.keys(areaMeasurements).forEach(areaId => {
            let area = areaMeasurements[areaId];
            $('#areaList .list-group-item.text-muted').remove();

            $('#areaList').append(`
                <div class="list-group-item d-flex justify-content-between align-items-center" data-id="${areaId}">
                    <span class="areaName">${area.name}</span>
                    <div>
                        <button class="btn btn-sm text-primary editArea" data-id="${areaId}">✎</button>
                        <button class="btn btn-sm text-danger removeArea" data-id="${areaId}">✕</button>
                    </div>
                </div>
            `);
        });
        renderAllAreas();
    }

    /* -------------------------
       TOGGLE AREA INPUT
    ------------------------- */
    $('#toggleAreaInput').click(function(){
        $('#areaBox').toggleClass('d-none');
        $('#areaDropdownList').removeClass('d-none');
        $('#areaInput').focus();
    });

    /* -------------------------
       FILTER AREA LIST
    ------------------------- */
    $('#areaInput').keyup(function(){
        let val = $(this).val().toLowerCase();
        $('.area-option').each(function(){
            $(this).toggle($(this).text().toLowerCase().includes(val));
        });
    });

    /* -------------------------
       ADD AREA
    ------------------------- */
    $(document).on('click','.area-option', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let name = $(this).text().trim();
        addAreaToList(id, name);
    });

    /* ADD NEW AREA */
    $('#areaInput').keypress(function(e){
        if(e.which === 13){
            e.preventDefault();
            let name = $(this).val().trim();
            if(name === '') return;

            let exists = false;
            $('.area-option').each(function(){
                if($(this).text().toLowerCase() === name.toLowerCase()){
                    exists = true;
                }
            });
            if(exists) return;

            $.post(window.routes.areaStore, {
                _token: window.csrfToken,
                name: name
            }, function(data){
                addAreaToList(data.id, data.name);
            }).fail(function(){
                alert('Failed to add area');
            });
        }
    });

    function addAreaToList(id, name){
        if(areaMeasurements[id]) return;

        areaMeasurements[id] = {
            name: name,
            rows: [
                { ref:'', unit:'CM', width:'', length:'', height:'', qty:1, remark:'' }
            ]
        };

        $('#areaList .list-group-item.text-muted').remove();

        $('#areaList').append(`
            <div class="list-group-item d-flex justify-content-between align-items-center" data-id="${id}">
                <span class="areaName">${name}</span>
                <div>
                    <button class="btn btn-sm text-primary editArea" data-id="${id}">✎</button>
                    <button class="btn btn-sm text-danger removeArea" data-id="${id}">✕</button>
                </div>
            </div>
        `);

        $('#areaBox').addClass('d-none');
        $('#areaInput').val('');

        renderAllAreas();
    }

    /* -------------------------
       EDIT AREA NAME
    ------------------------- */
    $(document).on('click','.editArea',function(){
        let id = $(this).data('id');
        let parent = $(this).closest('.list-group-item');
        let currentName = parent.find('.areaName').text().trim();

        parent.find('.areaName').replaceWith(`
            <input type="text"
                   class="form-control form-control-sm areaEditInput"
                   value="${currentName}"
                   data-id="${id}">
        `);

        $(this).hide();
    });

    $(document).on('keydown','.areaEditInput',function(e){
        if(e.key !== 'Enter') return;

        let input = $(this);
        let id = input.data('id');
        let newName = input.val().trim();
        if(newName === '') return;

        $.post(window.routes.areaUpdate,{
            _token : window.csrfToken,
            id     : id,
            name   : newName
        })
        .done(function(res){
            input.replaceWith(`<span class="areaName">${res.name}</span>`);
            $(`.editArea[data-id="${id}"]`).show();

            if (areaMeasurements[id]) {
                areaMeasurements[id].name = res.name;
            }

            renderAllAreas();
        })
        .fail(function(xhr){
            alert(xhr.responseJSON?.message || 'Failed to update area');
        });
    });

    /* -------------------------
       REMOVE AREA
    ------------------------- */
    $(document).on('click','.removeArea',function(){
        let id = $(this).data('id');
        let row = $(this).closest('.list-group-item');

        if(!confirm('Delete this area?')) return;

        $.post(window.routes.areaDelete, {
            _token: window.csrfToken,
            id: id
        }, function () {
            delete areaMeasurements[id];
            row.remove();

            if(Object.keys(areaMeasurements).length === 0){
                $('#areaList').append(`
                    <div class="list-group-item text-muted small">
                        Select or Add Area
                    </div>
                `);
            }

            renderAllAreas();

        }).fail(function (xhr) {
            alert(xhr.responseJSON?.message || 'Unable to delete area');
        });
    });

    /* -------------------------
       RENDER ALL AREAS
    ------------------------- */
    function renderAllAreas(){
        let container = $('#areaTables');
        container.empty();

        const areaIds = Object.keys(areaMeasurements);
        if(areaIds.length === 0){
            container.html(`
                <div class="p-4">
                    <div class="empty-state-box">
                        No measurements found
                    </div>
                </div>
            `);
            return;
        }

        areaIds.forEach(areaId => {
            let area = areaMeasurements[areaId];

            container.append(`
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-1 mb-md-0 text-dark">${area.name}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="Fill Area Reference, Unit, Width, Height, and Quantity. Length is optional. Width must be entered manually.">
                                <i class="bi bi-info-circle"></i>
                            </span>
                            <button class="btn btn-outline-primary btn-sm addRow" data-id="${areaId}">
                                Add Measurement
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Area Reference <span class="text-danger">*</span></th>
                                    <th>Unit <span class="text-danger">*</span></th>
                                    <th>Length</th>
                                    <th>Width <span class="text-danger">*</span></th>
                                    <th>Height</th>
                                    <th>Quantity <span class="text-danger">*</span></th>
                                    <th>Remark</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                ${area.rows.map((row,i)=>generateRow(areaId,row,i)).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `);
        });

        initTooltips();
    }

    function generateRow(areaId,row,index){
        return `
            <tr data-area="${areaId}" data-index="${index}">
                <td><input class="form-control ref" value="${row.ref || ''}"></td>
                <td>
                    <select class="form-control unit">
                        <option ${row.unit==='CM'?'selected':''}>CM</option>
                        <option ${row.unit==='INCH'?'selected':''}>INCH</option>
                        <option ${row.unit==='FT'?'selected':''}>FT</option>
                    </select>
                </td>
                <td><input type="number" class="form-control length" value="${row.length || ''}"></td>
                <td><input type="number" class="form-control width" value="${row.width || ''}"></td>
                <td><input type="number" class="form-control height" value="${row.height || ''}"></td>
                <td><input type="number" class="form-control qty" value="${row.qty || 1}"></td>
                <td><input class="form-control remark" value="${row.remark || ''}"></td>
                <td>
                    <button class="btn btn-light text-danger removeRow">🗑</button>
                </td>
            </tr>
        `;
    }

    /* -------------------------
       ADD ROW
    ------------------------- */
    $(document).on('click','.addRow',function(){
        let areaId = $(this).data('id');
        areaMeasurements[areaId].rows.push(
            { ref:'', unit:'CM', width:'', length:'', height:'', qty:1, remark:'' }
        );
        renderAllAreas();
    });

    /* -------------------------
       REMOVE ROW
    ------------------------- */
    $(document).on('click','.removeRow',function(){
        let tr = $(this).closest('tr');
        let areaId = tr.data('area');
        let index  = tr.data('index');

        let row = areaMeasurements[areaId].rows[index];

        if(row.id){
            $.post(window.routes.measurementDelete, {
                _token : window.csrfToken,
                id     : row.id
            });
        }

        areaMeasurements[areaId].rows.splice(index, 1);
        renderAllAreas();
    });

    /* -------------------------
       UPDATE DATA
    ------------------------- */
    $(document).on('change','.ref,.unit,.width,.length,.height,.qty,.remark',function(){
        let tr = $(this).closest('tr');
        let areaId = tr.data('area');
        let index = tr.data('index');

        areaMeasurements[areaId].rows[index] = {
            id: areaMeasurements[areaId].rows[index].id || null,
            ref: tr.find('.ref').val(),
            unit: tr.find('.unit').val(),
            width: tr.find('.width').val(),
            length: tr.find('.length').val(),
            height: tr.find('.height').val(),
            qty: tr.find('.qty').val(),
            remark: tr.find('.remark').val()
        };
    });

    /* -------------------------
       SUBMIT
    ------------------------- */
    $('#nextStep').click(function(){
        if(Object.keys(areaMeasurements).length === 0){
            alert('Please add at least one area');
            return;
        }

        if (!validateMeasurements()) {
            alert('Please fill all required fields');
            return;
        }

        $.post(window.routes.storeStep2,{
            _token: window.csrfToken,
            measurements: areaMeasurements
        },function(){
            window.location.href = window.routes.step3;
        }).fail(function(){
            alert('Failed to save measurements');
        });
    });

    // Tooltips
    function initTooltips(){
        var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );

        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // PDF download
    $('#downloadPdf').on('click', function () {
        if (Object.keys(areaMeasurements).length === 0) {
            alert('No measurements to export');
            return;
        }

        const form = $('<form>', {
            method: 'POST',
            action: window.routes.measurementsPdf
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: window.csrfToken
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: 'measurements',
            value: JSON.stringify(areaMeasurements)
        }));

        $('body').append(form);
        form.submit();
    });

});
