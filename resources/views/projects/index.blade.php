@extends('layouts.app')
@section('title', 'Projects | FurnishPro')
<style>
 .table-responsive {
    overflow-x: auto;
    overflow-y: visible !important;
}

.dropdown-menu {
    z-index: 9999;
}
</style>
@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm rounded-4">

            {{-- Header --}}
           <div class="d-flex justify-content-between align-items-center px-4 py-3">
    <h3 class="mb-0 text-black">Orders</h3>
</div>

            <div class="border-top"></div>

            {{-- FULL BORDER BOX --}}
            <div class="px-4 py-3">

                <div class="border rounded-3 overflow-hidden">

                    {{-- Header row --}}
                   <div class="d-flex justify-content-between align-items-center px-3 py-2">

    <span class="details">Order Details</span>

    <div class="d-flex align-items-center gap-2">

        {{-- ADD PROJECT --}}
        <a href="{{ route('projects.start') }}" class="btn btn-sm btn-green">
            + Add Project
        </a>

        {{-- ACTION DROPDOWN --}}
       <div class="dropdown">
    <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        Action
    </button>

    <ul class="dropdown-menu dropdown-menu-end p-1 box">

        <li>
            <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
                href="{{ route('projects.export', 'pdf') }}?search={{ $search ?? '' }}">
                <i class="bi bi-file-earmark-pdf text-danger"></i>
                <span>PDF</span>
            </a>
        </li>

        <li>
            <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
                href="{{ route('projects.export', 'csv') }}?search={{ $search ?? '' }}">
                <i class="bi bi-filetype-csv text-primary"></i>
                <span>CSV</span>
            </a>
        </li>

        <li>
            <a class="dropdown-item py-1 small d-flex align-items-center gap-2"
                href="{{ route('projects.export', 'excel') }}?search={{ $search ?? '' }}">
                <i class="bi bi-file-earmark-excel text-success"></i>
                <span>Excel</span>
            </a>
        </li>

    </ul>
</div>

    </div>

</div>

                    <div class="border-top"></div>

                    {{-- Search row --}}
                    <div class="px-3 py-2">
                        <form method="GET" action="{{ route('projects.index') }}">
                            <div class="d-flex justify-content-between align-items-center">

                                {{-- LEFT : Search --}}
                                <div style="width:300px">
                                    <input type="text" name="search" value="{{ $search ?? '' }}"
                                        class="form-control form-control-sm"
                                        placeholder="Search projects by name or customer...">
                                </div>

                                {{-- RIGHT : Per page filter --}}
                                <div>
                                    <select name="per_page" class="form-select form-select-sm" style="width:90px"
                                        onchange="this.form.submit()">

                                        <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>

                                    </select>
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="border-top"></div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th class="ps-3">Project Name</th>
                                    <th>Order ID</th>

                                    <th>Customer</th>
                                    {{-- <th>Total Amount</th>
                                    <th>Received</th>
                                    <th>Remaining</th>
                                    <th>Date</th> --}}
                                    <th class="sticky-status">Status</th>
                                    <th class="sticky-quotation">Quotation</th>
                                    <th class="text-end pe-3 sticky-action">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td class="ps-3">
                                            {{ $projects->firstItem() + $loop->index }}
                                        </td>
                                        <td class="ps-3 ">
                                           @php
    $projectFirst = explode(' ', $project->project_name)[0] ?? '-';
@endphp

<a href="{{ route('projects.create', ['step' => 0, 'project_id' => $project->id]) }}"
   class="text-decoration-none text-dark"
   title="{{ $project->project_name }}">
    {{ $projectFirst }}
</a>
                                        </td>
                                        <td>
                                            {{ $project->order_id ?? '-' }}
                                        </td>

                                        <td>
                                          @if ($project->customer)

@php
    $customerFirst = explode(' ', $project->customer->name)[0];
@endphp

<a href="#"
   class="text-decoration-none text-dark"
   title="{{ $project->customer->name }}">
    {{ $customerFirst }}
</a>

@else
-
@endif
                                        </td>

                                        {{-- <td>
                                            ₹ {{ number_format($project->total_amount ?? 0, 2) }}
                                        </td> --}}
{{--
                                        <td class="text-success">
                                            ₹ {{ number_format($project->received_amount ?? 0, 2) }}
                                        </td> --}}

                                        {{-- <td class="{{ $project->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                            ₹ {{ number_format(max(0, $project->remaining_amount ?? 0), 2) }}
                                        </td> --}}
{{--
                                        <td>
                                            {{ $project->created_at->format('d M Y') }}
                                        </td> --}}
 <td class="sticky-status">

<div class="dropdown">

<button
class="btn btn-sm btn-light border dropdown-toggle status-dropdown"
type="button"
data-bs-toggle="dropdown"
data-project-id="{{ $project->id }}"
data-status="{{ $project->status }}">

{{ ucfirst(str_replace('_',' ',$project->status)) }}

</button>

<ul class="dropdown-menu dropdown-menu-end menu" style="max-height:250px; overflow-y:auto;">

<li><a class="dropdown-item status-option" data-status="pending">Pending</a></li>
<li><a class="dropdown-item status-option" data-status="confirmed">Confirmed</a></li>
<li><a class="dropdown-item status-option" data-status="goods_ordered">Goods Ordered</a></li>
<li><a class="dropdown-item status-option" data-status="assign_to_tailors">Assign to Tailors</a></li>
<li><a class="dropdown-item status-option" data-status="in_production">In Production</a></li>
<li><a class="dropdown-item status-option" data-status="order_ready">Order Ready</a></li>
<li><a class="dropdown-item status-option" data-status="dispatch">Dispatch</a></li>
<li><a class="dropdown-item status-option" data-status="delivered">Delivered</a></li>
<li><a class="dropdown-item status-option text-danger" data-status="cancelled">Cancelled</a></li>

</ul>

</div>

</td>





                                        <td style="width:120px; max-width:120px; white-space:nowrap;">

                                            {{-- QUOTATION VERSIONS --}}
                                            @if ($project->quotations->count())
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Quotations
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end p-1 quotation">

                                                        @foreach ($project->quotations->sortByDesc('version') as $quotation)
                                                            <li class="px-2 py-1 small d-flex justify-content-between align-items-center w-100">

    <span class="text-truncate me-3">
        Version {{ $quotation->version }}
    </span>

    <span class="d-flex gap-2 flex-shrink-0">

        {{-- VIEW --}}
        <a href="{{ route('projects.quotation.pdf.view', [$project->id, 'quotation_id' => $quotation->id]) }}"
           target="_blank"
           class="text-secondary text-decoration-none"
           title="View PDF">
            <i class="bi bi-eye"></i>
        </a>

        {{-- DOWNLOAD --}}
        <a href="{{ route('projects.quotation.pdf', [$project->id, 'quotation_id' => $quotation->id]) }}"
           target="_blank"
           class="text-secondary text-decoration-none"
           title="Download PDF">
            <i class="bi bi-download"></i>
        </a>

    </span>

</li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif

                                        </td>


                                        <td class="text-end pe-3 sticky-action">

                                            {{-- VIEW --}}
<a href="{{ route('projects.create', ['step' => 0, 'project_id' => $project->id]) }}"
   class="text-secondary me-2">
    <i class="bi bi-eye"></i>
</a>

                                            {{-- APPROVE BUTTON --}}
                                            @if ($project->received_amount >= 0 && $project->status !== 'confirmed')
                                                <form action="{{ route('projects.approve', $project->id) }}"
                                                    method="POST" class="d-inline me-2">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn p-0 border-0 bg-transparent text-secondary"
                                                        onclick="return confirm('Approve this project?')">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- DELETE --}}
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-secondary" title="Delete"
                                                    onclick="return confirm('Delete this project?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            No projects found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($projects->hasPages())
                        <div class="border-top d-flex justify-content-end align-items-center gap-4 px-3 py-3">

                            <div class="text-muted small mb-3">
                                {{ $projects->firstItem() }}–{{ $projects->lastItem() }}
                                of {{ $projects->total() }}
                            </div>

                            <div>
                                {{ $projects->links() }}
                            </div>

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>
   <script>
document.addEventListener('DOMContentLoaded', function () {

document.querySelectorAll('.status-option').forEach(option => {

option.addEventListener('click', function () {

const status = this.dataset.status;
const dropdown = this.closest('.dropdown');
const button = dropdown.querySelector('.status-dropdown');
const projectId = button.dataset.projectId;

button.innerText = this.innerText;
button.dataset.status = status;

fetch(`/projects/${projectId}/status`, {
method: 'POST',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': '{{ csrf_token() }}',
'Accept': 'application/json'
},
body: JSON.stringify({ status: status })
})
.then(response => response.json())
.then(data => {
console.log('Status updated');
})
.catch(error => {
console.error('Status update error:', error);
});

});

});

});
</script>


@endsection
