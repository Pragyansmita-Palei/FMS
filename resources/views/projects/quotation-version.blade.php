{{-- resources/views/projects/quotation-version.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Quotation Version #{{ $versionInfo['version'] }}</h4>
        <div>
            <span class="badge bg-secondary">Created: {{ $versionInfo['created_at'] }}</span>
            <span class="badge bg-info">By: {{ $versionInfo['created_by'] }}</span>
            <a href="{{ route('projects.create', ['step' => 4, 'project_id' => $project->id]) }}" 
               class="btn btn-outline-primary ms-3">
                Back to Current Quotation
            </a>
        </div>
    </div>

    @foreach($materialsByArea as $area => $refs)
    <div class="card p-4 mb-4">
        <h5 class="area-title">{{ $area }}</h5>
        
        @foreach($refs as $ref => $items)
        <div class="reference-title">
            Reference: <span class="ref-badge">{{ $ref }}</span>
        </div>
        
        <div class="table-responsive quotation-wrapper">
            <table class="table quotation-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Length</th>
                        <th>Breadth</th>
                        <th>Width</th>
                        <th>Height</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Tax %</th>
                        <th>Discount %</th>
                        <th>Sale Rate</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product?->name ?? 'N/A' }}</td>
                        <td>{{ $item->length }}</td>
                        <td>{{ $item->breadth }}</td>
                        <td>{{ $item->width }}</td>
                        <td>{{ $item->height }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->rate, 2) }}</td>
                        <td>{{ $item->tax_rate }}%</td>
                        <td>{{ $item->discount }}%</td>
                        <td>{{ number_format($item->sale_rate, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endforeach

    <div class="card mt-4 p-4 text-end bg-light">
        <h4>Grand Total : ₹ {{ number_format($grandTotal, 2) }}</h4>
    </div>
</div>
@endsection