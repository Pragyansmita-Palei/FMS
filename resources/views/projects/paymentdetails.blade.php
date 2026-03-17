@extends('layouts.app')

@section('content')

<style>
.form-select:focus{
    border-color:#dee2e6 !important;
    box-shadow:none !important;
}
</style>
<div class="container-fluid">

<div class="card shadow border-0 rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header bg-white border-bottom">

        <div class="row g-2 align-items-center">

            {{-- Title --}}
            <div class="col-12 col-lg-4">
                <h5 class="mb-0 fw-bold">Payments Report</h5>
            </div>

           {{-- Filters --}}
<div class="col-12 col-lg-8">

    <div class="d-flex flex-column flex-sm-row justify-content-lg-end align-items-stretch gap-2">

        {{-- Project Dropdown --}}
        <form method="GET" action="{{ route('payment.details') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">

  <div class="dropdown">
    <button class="btn btn-sm btn-light border dropdown-toggle w-100"
        type="button"
        data-bs-toggle="dropdown">

        {{ request('project_id')
            ? $projects->where('id', request('project_id'))->first()->project_name
            : 'All Projects' }}
    </button>

    <ul class="dropdown-menu" style="max-height:250px; overflow-y:auto;">

        <li>
            <a class="dropdown-item"
               href="{{ route('payment.details', ['status'=>request('status')]) }}">
               All Projects
            </a>
        </li>

        @foreach($projects as $project)
        <li>
            <a class="dropdown-item"
               href="{{ route('payment.details', [
                    'project_id'=>$project->id,
                    'status'=>request('status')
               ]) }}">
               {{ $project->project_name }}
            </a>
        </li>
        @endforeach

    </ul>
</div>
        </form>


        {{-- Status Dropdown --}}
        <form method="GET" action="{{ route('payment.details') }}">
            <input type="hidden" name="project_id" value="{{ request('project_id') }}">

            <div class="dropdown">

    <button class="btn btn-sm btn-light border dropdown-toggle w-100"
        type="button"
        data-bs-toggle="dropdown">

        {{ request('status') ?? 'All' }}
    </button>

    <ul class="dropdown-menu">

        <li>
            <a class="dropdown-item"
               href="{{ route('payment.details', ['project_id'=>request('project_id')]) }}">
               All
            </a>
        </li>

        <li>
            <a class="dropdown-item"
               href="{{ route('payment.details', [
                    'status'=>'Confirmed',
                    'project_id'=>request('project_id')
               ]) }}">
               Confirmed
            </a>
        </li>

        <li>
            <a class="dropdown-item"
               href="{{ route('payment.details', [
                    'status'=>'Delivered',
                    'project_id'=>request('project_id')
               ]) }}">
               Delivered
            </a>
        </li>

    </ul>

</div>
        </form>

    </div>

</div>

        </div>

    </div>


    <div class="card-body">

        {{-- ================= SUMMARY CARDS ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-6 col-md-3">
                <div class="card border rounded-3">
                    <div class="card-body py-3">
                        <div class="small text-muted">Total Projects</div>
                        <h5 class="fw-bold mb-0">{{ $totalProjects ?? 0 }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border rounded-3">
                    <div class="card-body py-3">
                        <div class="small text-muted">Total Amount</div>
                        <h6 class="fw-bold text-info mb-0">
                            ₹{{ number_format($totalAmount ?? 0,2) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border rounded-3">
                    <div class="card-body py-3">
                        <div class="small text-muted">Received</div>
                        <h6 class="fw-bold text-success mb-0">
                            ₹{{ number_format($receivedAmount ?? 0,2) }}
                        </h6>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border rounded-3">
                    <div class="card-body py-3">
                        <div class="small text-muted">Pending</div>
                        <h6 class="fw-bold text-danger mb-0">
                            ₹{{ number_format($pendingAmount ?? 0,2) }}
                        </h6>
                    </div>
                </div>
            </div>

        </div>


        {{-- ================= FULL BORDER BOX (LIKE STORES PAGE) ================= --}}
        <div class="px-3 py-2">

            <div class="border rounded-3 overflow-hidden">

                {{-- Header Row --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="fw-semibold">Payment Details</span>
                </div>

                <div class="border-top"></div>

                {{-- Search Row --}}
                <div class="px-3 py-2">

                    <div class="row g-2">

                        <div class="col-md-4">
                            <input type="text"
                                class="form-control form-control-sm"
                                placeholder="Search project / customer">
                        </div>

                        <div class="col-md-2 ms-auto">
                            <select class="form-select form-select-sm">
                                <option selected>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="border-top"></div>

                {{-- TABLE --}}
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Project / Customer</th>
                                <th>Status</th>
                                <th>Amount (₹)</th>
                                <th>Received</th>
                                <th>Due</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                        @forelse($payments as $row)

                            <tr>

                                <td class="ps-3">
                                    <div class="fw-semibold">{{ $row->project_name }}</div>
                                    <div class="text-muted small">{{ $row->customer_name }}</div>
                                </td>

                               <td class="
    @if($row->status == 'Confirmed') text-primary
    @elseif($row->status == 'Delivered') text-success
    @else text-muted
    @endif
">
    {{ $row->status ?? '-' }}
</td>

                                <td>
                                    ₹{{ number_format($row->total_amount,2) }}
                                </td>

                                <td class="text-success">
                                    ₹{{ number_format($row->received_amount,2) }}
                                </td>

                                <td class="text-danger">
                                    ₹{{ number_format($row->due_amount,2) }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No records found
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $payments->links('pagination::bootstrap-5') }}
                </div>

            </div>

        </div>

    </div>

</div>

</div>

@endsection
