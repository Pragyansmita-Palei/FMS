$(function(){

    // ================= Validation Functions =================
    function validateField(field) {
        const value = $(field).val().trim();
        const isRequired = $(field).prop('required');
        const errorDiv = $(field).next('.validation-error');

        if (isRequired && !value) {
            $(field).addClass('is-invalid');
            if (errorDiv.length) errorDiv.text('This field is required').show();
            return false;
        } else {
            $(field).removeClass('is-invalid');
            if (errorDiv.length) errorDiv.hide();
            return true;
        }
    }

    function validateForm(form) {
        let isValid = true;
        const requiredFields = $(form).find('[required]');
        requiredFields.each(function() {
            if (!validateField(this)) isValid = false;
        });
        return isValid;
    }

    function setupValidation(fields) {
        fields.each(function() {
            const $field = $(this);
            const $errorDiv = $('<div class="validation-error"></div>');
            $field.after($errorDiv);

            $field.on('input change blur', function() {
                validateField(this);
            });
        });
    }

    // ================= Initialize Validation =================
    setupValidation($('#step1Form [required]'));
    setupValidation($('#customerForm [required]'));

    // ================= Load Customer Info =================
    $('#customer_id').change(function(){
        let id = $(this).val();
        if(!id){
            $('#phone, #email, #address, #project_name').val('');
            $('#cust_name_text').text('Customer Name');
            $('#cust_phone_text').text('');
            $('#cust_email_text').text('');
            $('#cust_short').text('PI');
            $('#customer_id, #project_name').removeClass('is-invalid');
            return;
        }

        $.get("{{ route('projects.customer', ':id') }}".replace(':id', id), function(data){
            $('#phone').val(data.phone);
            $('#email').val(data.email);
            $('#address').val(data.address_line1);
            $('#project_name').val(data.name + "'s Project #" + data.project_no);
            $('#cust_name_text').text(data.name);
            $('#cust_phone_text').text(data.phone);
            $('#cust_email_text').text(data.email);
            $('#cust_short').text(data.name.substring(0,2).toUpperCase());
            $('#customer_id, #project_name').removeClass('is-invalid');
        }).fail(function() {
            showToast('Error loading customer data', 'danger');
        });
    });

    // ================= Add / Edit Modal =================
    $('#addCustomerBtn').click(function(){
        $('#customerForm')[0].reset();
        $('#modal_customer_id').val('');
        $('#modal_title').text('Add New Customer');
        $('#customerModal').modal('show');
        $('#customerForm [required]').removeClass('is-invalid');
        $('#customerForm .validation-error').hide();
    });

    $('#editCustomerBtn').click(function(e){
        e.preventDefault();
        let id = $('#customer_id').val();
        if(!id){
            showToast('Please select a customer first', 'warning');
            $('#customer_id').addClass('is-invalid');
            return;
        }

        $.get("{{ route('projects.customer', ':id') }}".replace(':id', id), function(data){
            $('#modal_customer_id').val(data.id);
            $('#modal_name').val(data.name);
            $('#modal_phone').val(data.phone);
            $('#modal_email').val(data.email);
            $('#modal_address').val(data.address_line1);
            $('#modal_city').val(data.city);
            $('#modal_state').val(data.state);
            $('#modal_pin').val(data.pin);
            $('#modal_title').text('Edit Customer');
            $('#customerModal').modal('show');
            $('#customerForm [required]').removeClass('is-invalid');
            $('#customerForm .validation-error').hide();
        }).fail(function() {
            showToast('Error loading customer data', 'danger');
        });
    });

    // ================= Save Customer =================
    $('#customerForm').submit(function(e){
        e.preventDefault();
        if (!validateForm(this)) {
            showToast('Please fill all required fields', 'warning');
            return;
        }

        let id = $('#modal_customer_id').val();
        let url = id
            ? "{{ route('projects.customer.update', ':id') }}".replace(':id', id)
            : "{{ route('customers.store') }}";

        let formData = $(this).serialize();
        if(id) formData += '&_method=PUT';

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(res){
                $('#customerModal').modal('hide');
                let c = res.customer;
                if(!id){
                    $('#customer_id').append(`<option value="${c.id}" selected>${c.name}</option>`);
                    showToast('Customer added successfully ✅','success');
                } else {
                    $('#customer_id option:selected').text(c.name);
                    showToast('Customer updated successfully ✏️','success');
                }
                $('#customer_id').val(c.id).trigger('change');
            },
            error: function(xhr){
                let msg = 'Error saving customer';
                if(xhr.responseJSON?.errors){
                    msg = Object.values(xhr.responseJSON.errors)[0][0];
                }
                showToast(msg,'danger');
            }
        });
    });

    // ================= Step1 Form Submit =================
    $('#step1Form').submit(function(e){
        e.preventDefault();
        if (!validateForm(this)) {
            showToast('Please fill all required fields', 'warning');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                if(res.success){
                    window.location.href = "{{ route('projects.create') }}?step=2";
                }
            },
            error: function(xhr){
                let errorMsg = 'Error saving project. Please check required fields.';
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    const errors = xhr.responseJSON.errors;
                    for(const field in errors){
                        const $field = $(`[name="${field}"]`);
                        if($field.length){
                            $field.addClass('is-invalid');
                            const $errorDiv = $field.next('.validation-error');
                            if($errorDiv.length) $errorDiv.text(errors[field][0]).show();
                        }
                    }
                    errorMsg = Object.values(errors)[0][0];
                }
                showToast(errorMsg, 'danger');
            }
        });
    });

    // ================= Toast Function =================
    function showToast(message, type='success'){
        let toastContainer = $('.toast-container');
        $('.toast').remove();
        let bgClass = 'bg-success', icon = '✓';
        if(type==='danger'){ bgClass='bg-danger'; icon='✗'; }
        else if(type==='warning'){ bgClass='bg-warning text-dark'; icon='⚠'; }

        const toastHTML = `
            <div class="toast ${bgClass} text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <span class="me-2" style="font-size: 1.2rem;">${icon}</span>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        toastContainer.append(toastHTML);
        const toastEl = toastContainer.find('.toast').last()[0];
        const toast = new bootstrap.Toast(toastEl, {delay:3000, animation:true, autohide:true});
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', function(){ $(this).remove(); });
    }

    // ================= Real-time validation =================
    $('#project_name').on('input', function(){
        if($(this).val().trim()) $(this).removeClass('is-invalid');
    });

    // ================= Restore Session =================
    @if(!empty($step1Data['customer_id']))
    $('#customer_id').val('{{ $step1Data["customer_id"] }}').trigger('change');
    @endif

    $(document).ready(function(){
        $('#step1Form [required]').each(function(){ validateField(this); });
    });
});