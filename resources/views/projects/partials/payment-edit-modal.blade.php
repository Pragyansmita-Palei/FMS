<div class="modal fade" id="editPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">

            <form method="POST" id="editPaymentForm">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select class="form-select" name="payment_mode" id="edit_payment_mode" required>
                                <option>Cash</option>
                                <option>Card</option>
                                <option>UPI</option>
                                <option>Bank Transfer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Amount</label>
                            <input type="number" step="0.01"
                                   name="amount"
                                   id="edit_amount"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date"
                                   name="payment_date"
                                   id="edit_payment_date"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea
                                name="remarks"
                                id="edit_remarks"
                                class="form-control"
                                rows="3"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-green">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
