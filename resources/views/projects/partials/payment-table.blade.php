<div class="table-responsive">

    <table class="table align-middle table-hover mb-0">

        <thead>
            <tr class="border-bottom">
                <th class="fw-semibold text-muted">#</th>
                <th class="fw-semibold text-muted">Project Code</th>
                <th class="fw-semibold text-muted">Customer</th>
                <th class="fw-semibold text-muted">Final Amount</th>
                <th class="fw-semibold text-muted">Due Amount</th>
                <th class="fw-semibold text-muted">Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($projects as $project)

                <tr class="border-bottom">

                    {{-- Serial Number --}}
                    <td>{{ $projects->firstItem() + $loop->index }}</td>

                    <td class="text-primary fw-semibold">
                        {{ $project->project_name }}
                    </td>

                    <td>
                        {{ $project->customer->name ?? '-' }}
                    </td>

                    <td class="fw-semibold text-success">
                        ₹ {{ number_format($project->final_amount, 2) }}
                    </td>

                    <td class="fw-semibold text-danger">
                        ₹ {{ number_format($project->due_amount, 2) }}
                    </td>

                    <td class="fw-semibold">
                        @if($project->final_amount > 0 && $project->due_amount == 0)
                            <span class="text-warning">Pending</span>
                        @elseif($project->final_amount == 0 && $project->due_amount == 0)
                            <span class="text-success">Completed</span>
                        @elseif($project->final_amount > 0 && $project->due_amount > 0)
                            <span class="text-danger">Remaining</span>
                        @endif
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No Records Found
                    </td>
                </tr>
            @endforelse

        </tbody>

    </table>

</div>

{{-- ================= Pagination ================= --}}
@if ($projects->hasPages())
    <div class="d-flex justify-content-end p-3">
        {{ $projects->links() }}
    </div>
@endif
