@extends('layouts.app')
@section('title', 'Orders | FurnishPro')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h3 class="mb-0 text-black">Orders</h3>
        </div>

        <div class="border-top"></div>

        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th class="ps-3">Project Name</th>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="ps-3">
                                    {{ $projects->firstItem() + $loop->index }}
                                </td>

                                <td class="ps-3 fw-semibold">
                                    {{ $project->project_name ?? '-' }}
                                </td>

                                <td>

                                        <span class="fw-semibold text-muted">{{ $project->order_id ?? '-' }}</span>

                                </td>


                              <td class="fw-semibold
    @if($project->status === 'confirmed') text-success
    @elseif($project->status === 'pending') text-warning
    @else text-muted
    @endif">
    {{ ucfirst($project->status ?? 'Unknown') }}
</td>
                <td> @if($project->status === 'confirmed')
        <a href="{{ route('projects.received-payments', $project->id) }}"
           class="text-warning"
           title="Receive Payment">
payment        </a>
    @endif</td>

<td class="text-end pe-3">

    {{-- Generate Invoice --}}
    @if($project->invoices->isEmpty())
        <a href="{{ route('orders.invoice.generate',$project->id) }}"
           class="me-2"
           style="color:#6c757d;"
           title="Generate Invoice">
            <i class="fas fa-file-invoice"></i>
        </a>
    @else
        {{-- <span class="text-success me-2" title="Invoice Generated">
            <i class="fas fa-check-circle"></i>
        </span> --}}
    @endif

    {{-- Download & WhatsApp --}}
    @foreach($project->invoices as $invoice)

        {{-- Download --}}
        <a href="{{ route('orders.invoice.download',$invoice->id) }}"
           class="text-secondary me-2"
           title="Download Invoice {{ $invoice->invoice_no }}">
            <i class="bi bi-download"></i>
        </a>
        {{-- Print --}}
<a href="{{ route('orders.invoice.print',$invoice->id) }}"
   target="_blank"
   class="me-2"
   style="color:#6c757d;"
   title="Print Invoice {{ $invoice->invoice_no }}">
    <i class="fas fa-print"></i>
</a>

        {{-- WhatsApp --}}
        @if(optional($project->customer)->phone)
            <a target="_blank"
               class="text-success me-2"
               title="Send via WhatsApp"
               href="https://wa.me/91{{ preg_replace('/\D/','',$project->customer->phone) }}?text={{ urlencode('Dear '.$project->customer->name.', your invoice '.$invoice->invoice_no.' is ready. Download here: '.route('orders.invoice.download',$invoice->id)) }}">
                <i class="fab fa-whatsapp"></i>
            </a>
        @endif

    @endforeach

</td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No projects found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if($projects->hasPages())
                    <div class="border-top d-flex justify-content-end px-3 py-2">
                        {{ $projects->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>



@endsection
