@extends('layouts.app')
@section('title', 'Payments | FurnishPro')
<style>
    .action-icon{
    color:#6c757d !important;
    font-size:16px;
}

.action-icon:hover{
    opacity:0.8;
}
</style>
@section('content')
<div class="container-fluid px-0">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-body p-0">

            <!-- Header Section -->
            <div class="bg-white p-4 border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div>
                            <h3 class="h5  text-dark mb-1">Received Payments</h3>
                            {{-- <p class="text-muted mb-0">Saw Your Payment Details Here.</p> --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-end gap-3">
                            <div class="text-end d-none d-md-block">
                                <div class="text-muted small mb-1">Due Amount</div>
                                <div class="h4 fw-bold text-danger">₹ {{ number_format($dueAmount, 2) }}</div>
                            </div>
                            <div class="vr d-none d-md-block" style="height: 40px;"></div>
                            <button class="btn btn-green "
        data-bs-toggle="modal"
        data-bs-target="#receivedPaymentModal"
        {{ $dueAmount <= 0 ? 'disabled' : '' }}>
    <i class="bi bi-plus-circle"></i>
    Add Payment
</button>

                        </div>
                    </div>
                </div>

                <!-- Mobile Due Amount -->
                <div class="d-md-none mt-3">
                    <div class="bg-light-danger rounded-3 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-semibold">Due Amount:</div>
                            <div class="badge bg-danger px-3 py-2 rounded-pill">
                                ₹ {{ number_format($dueAmount, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-semibold text-muted text-uppercase small border-0">#</th>
                            <th class="fw-semibold text-muted text-uppercase small border-0">Amount Received</th>
                            <th class="fw-semibold text-muted text-uppercase small border-0">Payment Date</th>
                            <th class="fw-semibold text-muted text-uppercase small border-0">Payment Mode</th>
                             <th class="fw-semibold text-muted text-uppercase small border-0">Transaction no</th>
                            <th class="fw-semibold text-muted text-uppercase small border-0">Remarks</th>
                            <th class="pe-4 fw-semibold text-muted text-uppercase small border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Define mode colors array
                            $modeColors = [
                                'Cash' => 'info',
                                'Bank Transfer' => 'success',
                                'Cheque' => 'warning',
                                'UPI' => 'primary',
                                'Credit Card' => 'dark',
                                'Debit Card' => 'secondary'
                            ];
                        @endphp

                        @forelse($payments as $payment)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="fw-bold text-success">₹ {{ number_format($payment->amount, 2) }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}
                                </div>
                            </td>
                          <td>
    {{ $payment->payment_mode ?? '-' }}
</td>

                         <td>
    {{ $payment->transaction_number ?? '-' }}
</td>
                            <td>
                                @if($payment->remarks)
                                <div class="text-truncate d-inline-block" style="max-width: 200px;"
                                      data-bs-toggle="tooltip"
                                      data-bs-placement="top"
                                      title="{{ $payment->remarks }}">
                                    {{ $payment->remarks }}
                                </div>
                                @else
                                <div class="text-muted">-</div>
                                @endif
                            </td>
 <td class="text-end pe-4">
    <div class="d-flex justify-content-end gap-3 align-items-center">

        <!-- Download -->
        <a href="{{ route('projects.payments.receipt', [$project->id, $payment->id]) }}"
           target="_blank"
           title="Download Receipt">
            <i class="bi bi-download action-icon"></i>
        </a>

        <!-- Edit -->
        <a href="#"
           class="edit-payment-btn"
           title="Edit"
           data-bs-toggle="modal"
           data-bs-target="#editPaymentModal"
           data-id="{{ $payment->id }}"
           data-mode="{{ $payment->payment_mode }}"
           data-amount="{{ $payment->amount }}"
           data-date="{{ $payment->payment_date }}"
           data-remarks="{{ $payment->remarks }}">
            <i class="bi bi-pencil-square action-icon"></i>
        </a>

        <!-- Delete -->
        <a href="#"
           title="Delete"
           data-bs-toggle="modal"
           data-bs-target="#deletePaymentModal"
           data-id="{{ $payment->id }}">
            <i class="bi bi-trash action-icon"></i>
        </a>

    </div>
</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <div class="mb-3">
                                        <i class="bi bi-wallet2 display-6 text-muted opacity-50"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">No payments found</h5>
                                    <p class="text-muted mb-0">Start by adding your first received payment</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @if($payments->hasPages())
            <div class="bg-white border-top p-3">
                <div class="d-flex justify-content-end">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            @if($payments->onFirstPage())
                            <li class="page-item disabled">
                                <div class="page-link">Previous</div>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $payments->previousPageUrl() }}">Previous</a>
                            </li>
                            @endif

                            @foreach(range(1, $payments->lastPage()) as $page)
                            <li class="page-item {{ $payments->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $payments->url($page) }}">{{ $page }}</a>
                            </li>
                            @endforeach

                            @if($payments->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $payments->nextPageUrl() }}">Next</a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <div class="page-link">Next</div>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@include('projects.partials.received-payment-modal')
@include('projects.partials.payment-edit-modal')
@include('projects.partials.payment-delete-modal')



<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {

        // EDIT MODAL
    const editModal = document.getElementById('editPaymentModal');

    if(editModal){
        editModal.addEventListener('show.bs.modal', function (event) {

            const button  = event.relatedTarget;

            const id      = button.getAttribute('data-id');
            const mode    = button.getAttribute('data-mode');
            const amount  = button.getAttribute('data-amount');
            const date    = button.getAttribute('data-date');
            const remarks = button.getAttribute('data-remarks');

            const form = document.getElementById('editPaymentForm');

            form.action =
                "{{ route('projects.received.payments.update', [$project->id,'__id__']) }}"
                    .replace('__id__', id);

            document.getElementById('edit_payment_mode').value = mode;
            document.getElementById('edit_amount').value = amount;
            document.getElementById('edit_payment_date').value = date;
            document.getElementById('edit_remarks').value = remarks ?? '';
        });
    }
 // DELETE MODAL
    const deleteModal = document.getElementById('deletePaymentModal');

    if(deleteModal){
        deleteModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');

            const form = document.getElementById('deletePaymentForm');

            form.action =
                "{{ route('projects.received.payments.destroy', [$project->id,'__id__']) }}"
                    .replace('__id__', id);
        });
    }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add click handlers for edit and delete buttons
        document.querySelectorAll('.btn-outline-primary').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Add your edit functionality here
                console.log('Edit clicked');
            });
        });

        document.querySelectorAll('.btn-outline-danger').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Add your delete functionality here
                if(confirm('Are you sure you want to delete this payment?')) {
                    console.log('Delete confirmed');
                }
            });
        });
    });
</script>
@endsection
