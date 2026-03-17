<form id="step1Form" method="POST" action="{{ route('projects.store.step1') }}">
    @csrf
    <div class="container-fluid px-0">


        <div class="row g-4">
            <!-- ================= LEFT : CUSTOMER ================= -->
            <div class="col-md-12">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body ">
                        <!-- Header -->
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="d-flex align-items-center">
                                <div class="bi bi-person-circle fs-4 text-primary">

                                </div>
                                <h6 class="mb-0 fw-semibold ms-3" style="color: #1e293b;">Customer Information</h6>
                            </div>
                            <button type="button" class="btn btn-sm btn-green " id="addCustomerBtn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" class="me-1">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                                Add New
                            </button>
                        </div>

                        <!-- Customer Selection -->
                        <div class="mb-3 ">
                            <label class="form-label fw-medium text-secondary mb-2">Select Customer  <span
                                    class="text-danger">*</span></label>
                            <select id="customer_id" name="customer_id" class="form-select form-select-lg rounded-3"
                                style="border-color: #e2e8f0;" required>
                                <option value="">-- Choose a customer --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}"
                                        {{ ($step1Data['customer_id'] ?? '') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Customer Info Display (when selected) -->
                        {{-- <div class="customer-info-card mt-4 p-3 rounded-3"
                            style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px; background-color: #1d5d41; color: white; font-weight: 600; font-size: 1.1rem;">
                                    <span id="cust_short">PI</span>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 fw-semibold" style="color: #1e293b;" id="cust_name_text">Customer
                                        Name</h6>
                                    <div class="small text-muted">
                                        <div id="cust_phone_text"></div>
                                        <div id="cust_email_text"></div>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <a href="#" id="editCustomerBtn"
                                        class="btn btn-sm btn-light rounded-pill px-3"
                                        style="border: 1px solid #e2e8f0;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" class="me-1">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z" />
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Contact Details -->
                        <div class="row  g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary mb-1">Phone Number</label>
                                <input type="text" id="phone" class="form-control rounded-3" readonly
                                    style="background-color: #f1f5f9; border: 1px solid #e2e8f0;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary mb-1">Email Address</label>
                                <input type="text" id="email" class="form-control rounded-3" readonly
                                    style="background-color: #f1f5f9; border: 1px solid #e2e8f0;">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-medium text-secondary mb-1">Address</label>
                            <textarea id="address" name="address" class="form-control rounded-3" rows="2"
                                style="border-color: #e2e8f0; resize: none;">{{ $step1Data['address'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= RIGHT : PROJECT ================= -->
            <div class="col-md-12">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body ">
                        <!-- Header -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="bi bi-calendar3 fs-4 text-primary"></div>
                            <h6 class="mb-0 fw-semibold ms-3" style="color: #1e293b;">Project Details</h6>
                        </div>
                       <div class="row g-3">

    <!-- Project Name -->
    <div class="mb-3 col-md-6">
        <label class="form-label fw-medium text-secondary mb-1">
            Project Name <span class="text-danger">*</span>
        </label>

        <input type="text"
            id="project_name"
            name="project_name"
            value="{{ $step1Data['project_name'] ?? '' }}"
            class="form-control form-control-lg rounded-3"
            style="border-color:#e2e8f0;"
            placeholder="e.g., Website Redesign Project">
    </div>

    <!-- Sales Associate -->
    <div class="mb-3 col-md-6">
        <label class="form-label fw-medium text-secondary mb-1">
            Assign Sales Associate <span class="text-danger">*</span>
        </label>

        <select name="sales_associate_id"
            class="form-select form-select-lg rounded-3 sales-associate-select"
            style="border-color:#e2e8f0;"
            required>

            <option value="">-- Select Sales Associate --</option>

            @foreach($sales as $associate)
                <option value="{{ $associate->id }}"
                    {{ ($step1Data['sales_associate_id'] ?? '') == $associate->id ? 'selected' : '' }}>
                    {{ $associate->name }}
                </option>
            @endforeach

        </select>
    </div>

</div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary mb-1">Project Start Date <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="project_start_date"
                                        value="{{ $step1Data['project_start_date'] ?? '' }}"
                                        class="form-control rounded-3"
                                        style="border-color: #e2e8f0;" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary mb-1">Estimated End Date <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="estimated_end_date"
                                        value="{{ $step1Data['estimated_end_date'] ?? '' }}"
                                        class="form-control rounded-3"
                                        style="border-color: #e2e8f0;"required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary mb-1">Project Deadline <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="date" name="project_deadline"
                                        value="{{ $step1Data['project_deadline'] ?? '' }}"
                                        class="form-control rounded-3"
                                        style="border-color: #e2e8f0;"required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary mb-1">Priority</label>
                                <select name="priority" class="form-select rounded-3 "
                                    style="border-color: #e2e8f0;">
                                    <option value="Low"
                                        {{ ($step1Data['priority'] ?? '') == 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium"
                                        {{ ($step1Data['priority'] ?? '') == 'Medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="High"
                                        {{ ($step1Data['priority'] ?? '') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Urgent"
                                        {{ ($step1Data['priority'] ?? '') == 'Urgent' ? 'selected' : '' }}>Urgent
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-medium text-secondary mb-1">Project Requirements <span
                                    class="text-danger">*</span></label>
                            <textarea name="project_requirement" class="form-control rounded-3" rows="4"
                                style="border-color: #e2e8f0; resize: vertical;"
                                placeholder="Please describe the project requirements in detail..."required>{{ $step1Data['project_requirement'] ?? '' }}</textarea>
                            <div class="form-text mt-2">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" class="me-1">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                Be specific about deliverables, timeline expectations, and any special requirements
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex justify-content-end align-items-center mt-5 gap-3">
            <!-- <button type="button" class="btn btn-light rounded-pill px-4 py-2" style="border: 1px solid #e2e8f0; font-weight: 500;">
            Cancel
        </button> -->
            <button type="submit" class="btn btn-green ">
                Save & Continue
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" class="ms-2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</form>

<!-- ================= ADD / EDIT CUSTOMER MODAL (Updated Design) ================= -->
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="customerForm">
            @csrf
            <input type="hidden" id="modal_customer_id">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pb-0"
                    style="background: linear-gradient(135deg, #1d5d41 0%, #238254 100%); border-radius: 20px 20px 0 0; padding: 1.5rem;">
                    <h5 class="modal-title text-white" id="modal_title">Add New Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="modal_name" name="name" class="form-control rounded-3"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="modal_phone" name="phone" class="form-control rounded-3"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email Address</label>
                            <input type="email" id="modal_email" name="email" class="form-control rounded-3">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Address Line <span
                                    class="text-danger">*</span></label>
                            <textarea id="modal_address" name="address_line1" class="form-control rounded-3" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">City <span class="text-danger">*</span></label>
                            <input type="text" id="modal_city" name="city" class="form-control rounded-3"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">State <span class="text-danger">*</span></label>
                            <input type="text" id="modal_state" name="state" class="form-control rounded-3"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">PIN Code <span class="text-danger">*</span></label>
                            <input type="text" id="modal_pin" name="pin" class="form-control rounded-3"
                                required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light " data-bs-dismiss="modal"
                       >Cancel</button>
                    <button type="submit" class="btn btn-green"
                        >
                        Save Customer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ================= TOAST CONTAINER ================= -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



<script>
    $(function() {
        // ================= Validation Functions =================
        function validateField(field) {
            const value = $(field).val().trim();
            const isRequired = $(field).prop('required');
            const errorDiv = $(field).next('.validation-error');

            if (isRequired && !value) {
                $(field).addClass('is-invalid');
                if (errorDiv.length) {
                    errorDiv.text('This field is required').show();
                }
                return false;
            } else {
                $(field).removeClass('is-invalid');
                if (errorDiv.length) {
                    errorDiv.hide();
                }
                return true;
            }
        }

        function validateForm(form) {
            let isValid = true;
            const requiredFields = $(form).find('[required]');

            requiredFields.each(function() {
                if (!validateField(this)) {
                    isValid = false;
                    // Scroll to first error
                    if (isValid === false) {
                        $('html, body').animate({
                            scrollTop: $(this).offset().top - 100
                        }, 500);
                    }
                }
            });

            return isValid;
        }

        function setupValidation(fields) {
            fields.each(function() {
                const $field = $(this);
                const $errorDiv = $('<div class="validation-error"></div>');
                $field.after($errorDiv);

                // Real-time validation
                $field.on('input change', function() {
                    validateField(this);
                });

                // Blur validation
                $field.on('blur', function() {
                    validateField(this);
                });
            });
        }

        // ================= Initialize Validation =================
        // Setup validation for step1 form
        setupValidation($('#step1Form [required]'));

        // Setup validation for modal form
        setupValidation($('#customerForm [required]'));

        // ================= Load Customer Info =================
        $('#customer_id').change(function() {
            let id = $(this).val();
            if (!id) {
                // Clear fields and remove validation
                $('#phone').val('');
                $('#email').val('');
                $('#address').val('');
                $('#project_name').val('');

                $('#cust_name_text').text('Customer Name');
                $('#cust_phone_text').text('');
                $('#cust_email_text').text('');
                $('#cust_short').text('PI');

                // Remove validation classes
                $('#customer_id').removeClass('is-invalid');
                return;
            }

            $.get("{{ route('projects.customer', ':id') }}".replace(':id', id), function(data) {
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#address').val(data.address_line1);
                $('#project_name').val(data.name + "'s Project #" + data.project_no);

                $('#cust_name_text').text(data.name);
                $('#cust_phone_text').text(data.phone);
                $('#cust_email_text').text(data.email);
                $('#cust_short').text(data.name.substring(0, 2).toUpperCase());

                // Remove validation classes after successful load
                $('#customer_id').removeClass('is-invalid');
                $('#project_name').removeClass('is-invalid');
            }).fail(function() {
                showToast('Error loading customer data', 'danger');
            });
        });

        // ================= Add Modal =================
        $('#addCustomerBtn').click(function() {
            $('#customerForm')[0].reset();
            $('#modal_customer_id').val('');
            $('#modal_title').text('Add New Customer');
            $('#customerModal').modal('show');

            // Clear validation classes
            $('#customerForm [required]').removeClass('is-invalid');
            $('#customerForm .validation-error').hide();
        });

        // ================= Edit Modal =================
        $('#editCustomerBtn').click(function(e) {
            e.preventDefault();

            let id = $('#customer_id').val();
            if (!id) {
                showToast('Please select a customer first', 'warning');
                $('#customer_id').addClass('is-invalid');
                return;
            }

            $.get("{{ route('projects.customer', ':id') }}".replace(':id', id), function(data) {
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

                // Clear validation classes
                $('#customerForm [required]').removeClass('is-invalid');
                $('#customerForm .validation-error').hide();
            }).fail(function() {
                showToast('Error loading customer data', 'danger');
            });
        });

        // ================= Save Customer =================
        $('#customerForm').submit(function(e) {
            e.preventDefault();

            if (!validateForm(this)) {
                showToast('Please fill all required fields', 'warning');
                return;
            }

            let id = $('#modal_customer_id').val();

            let url = id ?
                "{{ route('projects.customer.update', ':id') }}".replace(':id', id) :
                "{{ route('customers.store') }}";

            let formData = $(this).serialize();

            if (id) {
                formData += '&_method=PUT'; // REQUIRED
            }

            $.ajax({
                url: url,
                method: 'POST', // Laravel expects POST + spoofed PUT
                data: formData,

                success: function(res) {

                    $('#customerModal').modal('hide');

                    let c = res.customer;

                    if (!id) {
                        $('#customer_id').append(
                            `<option value="${c.id}" selected>${c.name}</option>`
                        );
                        showToast('Customer added successfully ✅', 'success');
                    } else {
                        $('#customer_id option:selected').text(c.name);
                        showToast('Customer updated successfully ✏️', 'success');
                    }

                    $('#customer_id').val(c.id).trigger('change');
                },

                error: function(xhr) {

                    let msg = 'Error saving customer';

                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                    }

                    showToast(msg, 'danger');
                }
            });
        });

        // ================= Step1 Form Submit =================
        $('#step1Form').submit(function(e) {
            e.preventDefault();

            // Validate form
            if (!validateForm(this)) {
                showToast('Please fill all required fields', 'warning');

                // Highlight all invalid fields
                $(this).find('[required]').each(function() {
                    validateField(this);
                });

                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        window.location.href = "{{ route('projects.create') }}?step=2";
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error saving project. Please check required fields.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Show validation errors from server
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            const $field = $(`[name="${field}"]`);
                            if ($field.length) {
                                $field.addClass('is-invalid');
                                const $errorDiv = $field.next('.validation-error');
                                if ($errorDiv.length) {
                                    $errorDiv.text(errors[field][0]).show();
                                }
                            }
                        }
                        errorMsg = Object.values(errors)[0][0];
                    }
                    showToast(errorMsg, 'danger');
                }
            });
        });

        // ================= Toast Function =================
        function showToast(message, type = 'success') {
            // Create toast element if it doesn't exist
            let toastContainer = $('.toast-container');

            // Remove existing toast
            $('.toast').remove();

            // Set background color based on type
            let bgClass = 'bg-success';
            let icon = '✓';

            if (type === 'danger') {
                bgClass = 'bg-danger';
                icon = '✗';
            } else if (type === 'warning') {
                bgClass = 'bg-warning text-dark';
                icon = '⚠';
            }

            // Create toast HTML
            const toastHTML = `
        <div class="toast ${bgClass} text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <span class="me-2" style="font-size: 1.2rem;">${icon}</span>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

            // Append toast to container
            toastContainer.append(toastHTML);

            // Initialize and show toast
            const toastEl = toastContainer.find('.toast').last()[0];
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000,
                animation: true,
                autohide: true
            });

            toast.show();

            // Remove toast from DOM after it hides
            toastEl.addEventListener('hidden.bs.toast', function() {
                $(this).remove();
            });
        }

        // ================= Real-time validation for project name =================
        $('#project_name').on('input', function() {
            if ($(this).val().trim()) {
                $(this).removeClass('is-invalid');
            }
        });

        // ================= Restore Session =================
        @if (!empty($step1Data['customer_id']))
            $('#customer_id').val('{{ $step1Data['customer_id'] }}').trigger('change');
        @endif

        // Trigger validation on page load for already filled fields
        $(document).ready(function() {
            // Validate all required fields on page load
            $('#step1Form [required]').each(function() {
                validateField(this);
            });
        });

    });
</script>
