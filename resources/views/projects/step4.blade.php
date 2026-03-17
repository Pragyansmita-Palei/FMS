@php
$projectId = $project->id;
@endphp

<div class="dashboard-container">

    @foreach($materialsByArea as $area => $refs)

    <!-- Area Card -->
    <div class="area-card">

        <!-- Area Header with Stats -->
        <div class="area-header">
            <div class="area-title-section">
                <span class="area-marker"></span>
                <h3>{{ $area }}</h3>
            </div>
            <div class="area-stats">
                <span class="stat-item">
                    <span class="stat-label">Items</span>
                    <span class="stat-value">{{ collect($refs)->flatten()->count() }}</span>
                </span>
                <span class="stat-item">
                    <span class="stat-label">References</span>
                    <span class="stat-value">{{ count($refs) }}</span>
                </span>
            </div>
        </div>

        <!-- Reference Blocks -->
        @foreach($refs as $ref => $items)
        <div class="reference-block">

            <!-- Reference Header (Simplified - removed ref total) -->
            <div class="ref-header">
                <div class="ref-info">
                    <span class="ref-label">Reference</span>
                    <span class="ref-code">{{ $ref }}</span>
                </div>
                <!-- Ref total removed from here -->
            </div>

            <!-- Items Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="18%">Product</th>
                            <th width="12%">Dimensions</th>
                            <th width="8%">Unit</th>
                            <th width="8%">Qty</th>
                            <th width="8%">MRP (₹)</th>
                            <th width="8%">Tax (%)</th>
                            <th width="8%">Disc (%)</th>
                            <th width="10%">Sale Rate (₹)</th>
                            <th width="10%">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr data-id="{{ $item->id }}" class="item-row">
                            <td>
                                <div class="product-cell">
                                    <span class="product-name">{{ $item->product?->name ?? 'N/A' }}</span>
                                    @if($item->product?->code)
                                    <span class="product-code">{{ $item->product->code }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="dimension-display">
                                    {{ $item->length }}  × {{ $item->width }} × {{ $item->height }}
                                </span>
                            </td>
                            <td>
                                <select class="form-control unit-select unit material-select">
                                    <option value="CM" {{ $item->unit == 'CM' ? 'selected' : '' }}>CM</option>
                                    <option value="INCH" {{ $item->unit == 'INCH' ? 'selected' : '' }}>INCH</option>
                                    <option value="FT" {{ $item->unit == 'FT' ? 'selected' : '' }}>FT</option>
                                    <option value="M" {{ $item->unit == 'M' ? 'selected' : '' }}>M</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control qty-input qty" value="{{ $item->qty }}" min="0" step="0.01">
                            </td>
                            <td>
                                <input type="number" class="form-control rate-input rate" value="{{ $item->rate }}" min="0" step="0.01">
                            </td>
                            <td>
                                <input type="number" class="form-control tax-input tax" value="{{ $item->tax_rate ?? 0 }}" min="0" step="0.01">
                            </td>
                            <td>
                                <input type="number" class="form-control discount-input discount" value="{{ $item->discount }}" min="0" step="0.01">
                            </td>
                            <td class="sale-rate-column">
                                <span class="sale-rate-display">{{ number_format($item->sale_rate, 2) }}</span>
                            </td>
                            <td class="total-column">
                                <span class="total-display">{{ number_format($item->total, 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @endforeach

        <!-- Area Total at bottom of area card -->
        <div class="area-total-bottom">
            <div class="area-total-content">
                <span class="area-total-label">Area Total:</span>
                <span class="area-total-value" id="area-total-{{ Str::slug($area) }}">₹ 0.00</span>
            </div>
        </div>

    </div>
    @endforeach

    <!-- Footer Section with Two Columns -->
    <div class="dashboard-footer">
        <div class="footer-columns">
            <!-- Left Column - Terms & Conditions with Textarea -->
            <div class="terms-column">
                <div class="terms-header">
                    <h4>Terms & Conditions</h4>
                    <button class="hide-btn" onclick="toggleTerms()">Hide T&C</button>
                </div>
<div class="terms-content" id="termsContent">
<textarea class="terms-textarea" id="termsTextarea" placeholder="Enter terms and conditions here...">
{{ $term->description ?? '' }}
</textarea>
</div>
            </div>

            <!-- Right Column - Quote Summary -->
            <div class="summary-column">
                <div class="summary-card">
                    <h4>Quote Summary</h4>

                    <div class="summary-row">
                        <span>Sub Total</span>
                        <span class="summary-amount" id="subTotal">₹ 0.00</span>
                    </div>

                    <div class="summary-row">
                        <span>Total Tax Amount</span>
                        <span class="summary-amount" id="totalTax">₹ 0.00</span>
                    </div>

                    <div class="summary-row">
                        <span>Discount</span>
                        <span class="summary-amount" id="totalDiscount">₹ 0.00</span>
                    </div>

                    <div class="summary-row grand-total-row">
                        <span>Grand Total</span>
                        <span class="grand-total-amount" id="grandTotal">₹ 0.00</span>
                    </div>

                    {{-- <button class="generate-quote-btn btn-green" onclick="generateQuote(event)">
                        Update & Generate Quote
                    </button> --}}
                    {{-- <a target="_blank"
   href="{{ route('projects.quotation.preview', ['project' => $project->id, 'quotation_id' => $quotation->id]) }}">
    Preview Quotation (HTML)
</a> --}}
<div class="d-flex gap-2">

    <button class="generate-quote-btn btn-green" onclick="generateQuote(event)">
        Generate Quote
    </button>

    <button class="generate-quote-btn btn-green" onclick="printQuotation()">
        🖨 Print Quotation
    </button>

</div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
/* Base Styles */
.dashboard-container {
    max-width: 1400px;
    margin: 20px auto;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
}

/* Area Card */
.area-card {
    background: #ffffff;
    border: 1px solid #e9edf4;
    border-radius: 12px;
    margin-bottom: 30px;
    overflow: hidden;
}

.area-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 24px;
    background: #f8fafd;
    border-bottom: 1px solid #e9edf4;
}

.area-title-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.area-marker {
    width: 4px;
    height: 24px;
    background: #238254;
    border-radius: 4px;
}

.area-title-section h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.area-stats {
    display: flex;
    gap: 24px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stat-label {
    color: #64748b;
    font-size: 13px;
}

.stat-value {
    color: #1e293b;
    font-weight: 600;
    font-size: 14px;
    background: #ffffff;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

/* Reference Block */
.reference-block {
    padding: 20px 24px;
    border-bottom: 1px solid #edf2f7;
}

.reference-block:last-child {
    border-bottom: none;
}

.ref-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.ref-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.ref-label {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
}

.ref-code {
    background: #f1f5f9;
    color: #334155;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #e2e8f0;
}

/* Area Total Bottom */
.area-total-bottom {
    padding: 16px 24px;
    background: #f8fafd;
    border-top: 2px solid #e9edf4;
    text-align: right;
}

.area-total-content {
    display: inline-flex;
    align-items: center;
    gap: 15px;
    background: #ffffff;
    padding: 10px 20px;
    border-radius: 40px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.area-total-label {
    color: #475569;
    font-size: 15px;
    font-weight: 500;
}

.area-total-value {
    color: #238254;
    font-size: 20px;
    font-weight: 700;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
    border: 1px solid #e9edf4;
    border-radius: 10px;
    margin-bottom: 16px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px;
}

.data-table thead tr {
    background: #f8fafd;
    border-bottom: 2px solid #e9edf4;
}

.data-table thead th {
    padding: 14px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    text-align: left;
    white-space: nowrap;
}

.data-table tbody tr {
    border-bottom: 1px solid #eef2f6;
}

.data-table tbody tr:last-child {
    border-bottom: none;
}

.data-table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
}

/* Product Cell */
.product-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.product-name {
    font-weight: 500;
    color: #1e293b;
    font-size: 14px;
}

.product-code {
    color: #94a3b8;
    font-size: 11px;
}

/* Dimension Display */
.dimension-display {
    background: #f8fafc;
    padding: 4px 8px;
    border-radius: 6px;
    color: #475569;
    font-size: 13px;
    font-family: monospace;
    border: 1px solid #e2e8f0;
    display: inline-block;
}

/* Form Controls */
.unit-select {
    width: 90px;
    padding: 6px 8px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    background: white;
}

.qty-input, .rate-input, .tax-input, .discount-input {
    width: 90px;
    padding: 6px 8px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
}

.qty-input:focus, .rate-input:focus, .tax-input:focus, .discount-input:focus,
.unit-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Amount Columns */
.sale-rate-column, .total-column {
    background: #f8fafd;
}

.sale-rate-display {
    color: #059669;
    font-weight: 600;
    font-size: 14px;
}

.total-display {
    color: #1e293b;
    font-weight: 600;
    font-size: 14px;
}

/* Footer with Two Columns */
.dashboard-footer {
    margin-top: 40px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
}

.footer-columns {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

/* Terms Column with Textarea */
.terms-column {
    background: #f8fafd;
    border-radius: 10px;
    padding: 20px;
}

.terms-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.terms-header h4 {
    margin: 0;
    color: #1e293b;
    font-size: 16px;
    font-weight: 600;
}

.hide-btn {
    padding: 4px 12px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 15px;
    color: #64748b;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.hide-btn:hover {
    background: #e2e8f0;
}

.terms-textarea {
    width: 100%;
    min-height: 200px;
    padding: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    line-height: 1.6;
    color: #475569;
    background: #ffffff;
    resize: vertical;
    transition: border-color 0.2s;
}

.terms-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.terms-textarea::placeholder {
    color: #94a3b8;
}

/* Summary Column */
.summary-column {
    background: #ffffff;
    border: 1px solid #e9edf4;
    border-radius: 10px;
    padding: 20px;
}

.summary-card h4 {
    margin: 0 0 20px 0;
    color: #1e293b;
    font-size: 16px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9edf4;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #eef2f6;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row span:first-child {
    color: #64748b;
    font-size: 14px;
}

.summary-amount {
    color: #1e293b;
    font-weight: 600;
    font-size: 15px;
}

.grand-total-row {
    margin-top: 10px;
    padding-top: 15px;
    border-top: 2px solid #e9edf4;
}

.grand-total-amount {
    color: #2563eb;
    font-size: 20px;
    font-weight: 700;
}

.generate-quote-btn {
    width: 100%;
    margin-top: 20px;
    padding: 14px;
    /* background: #2563eb; */
    color: white;
    border: none;
    /* border-radius: 8px; */
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: background 0.2s;
}



.generate-quote-btn:disabled {
    background: #94a3b8;
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-icon {
    font-size: 16px;
}

/* Hidden class for terms */
.terms-content.hidden {
    display: none;
}

/* Scrollbar */
.table-container::-webkit-scrollbar {
    height: 6px;
}

.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive */
@media (max-width: 1024px) {
    .footer-columns {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .area-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .area-stats {
        flex-wrap: wrap;
    }

    .area-total-content {
        width: 100%;
        justify-content: space-between;
    }
}
</style>

<script>
const unitCostMap = {
    CM: 1,
    INCH: 2.54,
    FT: 30.48,
    M: 100
};

let currentQuotationId = {{ $quotation->id ?? 'null' }};
let updateTimeout;

function calculateRow(row) {
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const rate = parseFloat(row.querySelector('.rate').value) || 0;
    const discount = parseFloat(row.querySelector('.discount').value) || 0;
    const tax = parseFloat(row.querySelector('.tax').value) || 0;
    const unit = row.querySelector('.unit').value;

    const unitFactor = unitCostMap[unit] ?? 1;
    let baseRate = rate * unitFactor;
    let afterDiscount = baseRate - (baseRate * discount / 100);
    let saleRate = afterDiscount + (afterDiscount * tax / 100);
    let total = saleRate * qty;

    row.querySelector('.sale-rate-display').innerText = saleRate.toFixed(2);
    row.querySelector('.total-display').innerText = total.toFixed(2);
}

function updateTotals() {
    let grandTotal = 0;
    let subTotal = 0;
    let totalTax = 0;
    let totalDiscount = 0;

    document.querySelectorAll('.area-card').forEach((areaCard) => {
        let areaTotal = 0;

        areaCard.querySelectorAll('.item-row').forEach(row => {
            const rowTotal = parseFloat(row.querySelector('.total-display').innerText) || 0;
            const rowQty = parseFloat(row.querySelector('.qty').value) || 0;
            const rowRate = parseFloat(row.querySelector('.rate').value) || 0;
            const rowDiscount = parseFloat(row.querySelector('.discount').value) || 0;
            const rowTax = parseFloat(row.querySelector('.tax').value) || 0;
            const unit = row.querySelector('.unit').value;
            const unitFactor = unitCostMap[unit] ?? 1;

            // Calculate tax and discount amounts
            const baseAmount = rowRate * unitFactor * rowQty;
            const discountAmount = baseAmount * (rowDiscount / 100);
            const taxableAmount = baseAmount - discountAmount;
            const taxAmount = taxableAmount * (rowTax / 100);

            areaTotal += rowTotal;
            subTotal += baseAmount;
            totalDiscount += discountAmount;
            totalTax += taxAmount;
        });

        // Update area total at bottom of area card
        const areaTotalElement = areaCard.querySelector('.area-total-value');
        if (areaTotalElement) {
            areaTotalElement.innerText = '₹ ' + areaTotal.toFixed(2);
        }

        grandTotal += areaTotal;
    });

    // Update summary section
    document.getElementById('subTotal').innerText = '₹ ' + subTotal.toFixed(2);
    document.getElementById('totalTax').innerText = '₹ ' + totalTax.toFixed(2);
    document.getElementById('totalDiscount').innerText = '₹ ' + totalDiscount.toFixed(2);
    document.getElementById('grandTotal').innerText = '₹ ' + grandTotal.toFixed(2);
}

function debouncedUpdate(row, id, qty, rate, discount, tax, unit) {
    clearTimeout(updateTimeout);
    updateTimeout = setTimeout(() => {
        fetch("{{ route('quotation.item.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id,
                qty,
                rate,
                discount,
                tax,
                unit,
                terms: document.getElementById('termsTextarea').value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update current quotation ID if it changed
                if (data.new_quotation_id) {
                    currentQuotationId = data.new_quotation_id;

                    // Update URL with new quotation_id without page reload
                    const url = new URL(window.location);
                    url.searchParams.set('quotation_id', data.new_quotation_id);
                    window.history.replaceState({}, '', url);
                }

                // Update the row displays with server-calculated values
                if (data.grand_total !== undefined) {
                    document.getElementById('grandTotal').innerText = '₹ ' + data.grand_total.toFixed(2);
                    document.getElementById('subTotal').innerText = '₹ ' + data.sub_total.toFixed(2);
                    document.getElementById('totalTax').innerText = '₹ ' + data.total_tax.toFixed(2);
                    document.getElementById('totalDiscount').innerText = '₹ ' + data.total_discount.toFixed(2);
                }
            }
        })
        .catch(error => {
            console.error('Error updating item:', error);
        });
    }, 500); // Debounce for 500ms
}

function saveTerms() {
    const terms = document.getElementById('termsTextarea').value;

    if (!currentQuotationId) return;

    fetch("{{ route('quotation.save.terms') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            quotation_id: currentQuotationId,
            terms: terms
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log('Terms saved successfully');
        }
    })
    .catch(error => {
        console.error('Error saving terms:', error);
    });
}

function generateQuote(event) {
    // Save terms before generating PDF
    saveTerms();

    const projectId = {{ $projectId }};

    // Show loading state
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Generating PDF...';
    btn.disabled = true;

    // Construct the PDF URL with current quotation ID
    let pdfUrl = '/projects/' + projectId + '/quotation-pdf';
    if (currentQuotationId) {
        pdfUrl += '?quotation_id=' + currentQuotationId;
    }

    // Open PDF in new tab
    window.open(pdfUrl, '_blank');

    // Reset button after a short delay
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 2000);
}

function toggleTerms() {
    const termsContent = document.getElementById('termsContent');
    const hideBtn = event.target;

    if (termsContent.style.display === 'none' || termsContent.style.display === '') {
        termsContent.style.display = 'block';
        hideBtn.textContent = 'Hide T&C';
    } else {
        termsContent.style.display = 'none';
        hideBtn.textContent = 'Show T&C';
    }
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Load existing terms if available
    @if(isset($quotation) && $quotation->terms_and_conditions)
        document.getElementById('termsTextarea').value = {!! json_encode($quotation->terms_and_conditions) !!};
    @endif

    // Terms textarea change - auto-save after typing stops
    document.getElementById('termsTextarea').addEventListener('input', function() {
        clearTimeout(window.termsTimeout);
        window.termsTimeout = setTimeout(saveTerms, 1000);
    });

    document.querySelectorAll('.item-row').forEach(row => {
        row.querySelectorAll('.qty, .rate, .discount, .tax, .unit').forEach(input => {
            input.addEventListener('input', function() {
                calculateRow(row);
                updateTotals();

                const id = row.dataset.id;
                const qty = row.querySelector('.qty').value;
                const rate = row.querySelector('.rate').value;
                const discount = row.querySelector('.discount').value;
                const tax = row.querySelector('.tax').value;
                const unit = row.querySelector('.unit').value;

                debouncedUpdate(row, id, qty, rate, discount, tax, unit);
            });
        });
    });

    // Initial calculations
    document.querySelectorAll('.item-row').forEach(row => calculateRow(row));
    updateTotals();
});
function printQuotation() {

    const projectId = {{ $projectId }};
    let url = '/projects/' + projectId + '/quotation-preview';

    if (currentQuotationId) {
        url += '?quotation_id=' + currentQuotationId;
    }

    let printWindow = window.open(url, '_blank');

    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
    };
}
</script>
