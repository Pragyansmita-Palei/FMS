@extends('layouts.app')
@section('title', 'Manage Status | FurnishPro')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm rounded-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center px-4 py-3">
                <h5 class="mb-0 fw-semibold">Manage Status</h5>

                <button type="button" class="btn btn-green" data-bs-toggle="modal" data-bs-target="#statusModal">
                    + Add Status
                </button>
            </div>

            <div class="border-top"></div>

            {{-- FULL BORDER BOX --}}
            <div class="px-4 py-3">

                <div class="border rounded-3 overflow-hidden">

                    {{-- Status details header row --}}
                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                        <span class="fw-semibold">Status Details</span>
                    </div>

                    <div class="border-top"></div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Status Name</th>
                                    <th>Role</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($statuses as $status)
                                    <tr>
                                        <td class="ps-3">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $status->name }}</td>
                                        <td>{{ $status->role->name ?? '-' }}</td>

                                        <td class="text-end pe-3">

                                            {{-- Edit --}}
                                            <a href="javascript:void(0)"
                                                class="text-decoration-none text-secondary me-2 editBtn"
                                                data-id="{{ $status->id }}" data-name="{{ $status->name }}"
                                                data-role="{{ $status->role_id }}" data-bs-toggle="modal"
                                                data-bs-target="#statusModal" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('statuses.destroy', $status->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-secondary" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this status?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No statuses found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    {{-- <div class="border-top d-flex justify-content-end px-3 py-2">
                        {{ $statuses->links('pagination::bootstrap-5') }}
                    </div> --}}

                </div>

            </div>

        </div>

    </div>

    {{-- ================= ADD / EDIT STATUS MODAL ================= --}}
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form id="statusForm" method="POST" action="{{ route('statuses.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod">

                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label">
                                    Status Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="statusName" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">
                                    Select Role <span class="text-danger">*</span>
                                </label>
                                <select name="role_id" id="roleSelect" class="form-control" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-green">
                                Save Status
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ================= SCRIPT ================= --}}
    <script>
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', function() {

                document.querySelector('#statusModal .modal-title').innerText = "Edit Status";

                let form = document.getElementById('statusForm');
                form.action = "/statuses/" + this.dataset.id;

                document.getElementById('formMethod').value = "PUT";
                document.getElementById('statusName').value = this.dataset.name;
                document.getElementById('roleSelect').value = this.dataset.role;
            });
        });

        document.getElementById('statusModal').addEventListener('hidden.bs.modal', function() {

            document.querySelector('#statusModal .modal-title').innerText = "Add Status";

            let form = document.getElementById('statusForm');
            form.action = "{{ route('statuses.store') }}";

            document.getElementById('formMethod').value = "";
            form.reset();
        });
    </script>

@endsection
