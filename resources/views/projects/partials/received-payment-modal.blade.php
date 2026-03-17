<!-- Received Payment Modal (must NOT be inside any other form) -->
<div class="modal fade" id="receivedPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">

            <div class="modal-header px-4 py-3">
                <h5 class="modal-title fw-semibold">
                    Received Payments
                    🔴 Remaining Payment {{ number_format($dueAmount,2) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- IMPORTANT : this form must be outside any other form -->
            <form method="POST"
                  action="{{ route('projects.received.payments.store',$project->id) }}">
                @csrf

                <div class="modal-body px-4">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Payment Method <span class="text-danger">*</span>
                            </label>
                           <select class="form-select" name="payment_mode" id="payment_mode" required>
    <option value="">Select Payment Method</option>
    <option value="Cash">Cash</option>
    <option value="Card">Card</option>
    <option value="UPI">UPI</option>
    <option value="Bank Transfer">Bank Transfer</option>
</select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Payment Amount <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   step="0.01"
                                   name="amount"
                                   class="form-control"
                                   placeholder="Enter Amount"
                                   required>
                        </div>

                       <div class="col-md-12" id="transaction_number_group">
    <label class="form-label fw-semibold">
        Transaction Number <span class="text-danger">*</span>
    </label>

    <input type="text"
           name="transaction_number"
           id="transaction_number"
           class="form-control"
           placeholder="Enter transaction number">
</div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Payment Date <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="payment_date"
                                   class="form-control"
                                   value="{{ now()->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Enter any additional notes"></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4 pb-4">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-green">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function() {

    // Payment mode change
    $('#payment_mode').on('change', function () {

        let mode = $(this).val();

        if (mode === 'Cash') {
            $('#transaction_number_group').hide();
            $('#transaction_number').prop('required', false);
        } else {
            $('#transaction_number_group').show();
            $('#transaction_number').prop('required', true);
        }

    });

});

</script>
