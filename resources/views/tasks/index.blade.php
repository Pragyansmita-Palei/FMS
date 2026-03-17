@extends('layouts.app')
@section('title', 'Tasks | FurnishPro')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h3 class="mb-0 text-black">Tasks</h3>
        </div>

        <div class="border-top"></div>

        {{-- FULL BORDER BOX --}}
        <div class="px-4 py-3">
            <div class="border rounded-3 overflow-hidden">
                {{-- Details header row with tabs --}}
                <div class="d-flex justify-content-between align-items-center px-3 py-2">
                    <span class="details">Task Details</span>

                    <div class="d-flex align-items-center gap-2">
                        {{-- Status Tabs as Dropdown (for mobile/compact view) --}}
                        <div class="dropdown d-md-none">
                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Filter Status
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" id="taskStatusDropdown">
                                <li><a class="dropdown-item active" data-status="all" href="#">All Tasks</a></li>
                                <li><a class="dropdown-item" data-status="Assign To Tailor" href="#">Assign To Tailor</a></li>
                                <li><a class="dropdown-item" data-status="In Progress" href="#">In Progress</a></li>
                                <li><a class="dropdown-item" data-status="Completed" href="#">Completed</a></li>
                            </ul>
                        </div>

                        {{-- Add Task Button --}}
                        <button type="button" class="btn btn-green btn-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            + Add Task
                        </button>

                        {{-- Action Dropdown --}}
                        {{-- <div class="dropdown">
                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-1" style="min-width:160px">
                                <li>
                                    <a class="dropdown-item py-1 small" href="#">
                                        📊 Export Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-1 small" href="#">
                                        🗂 Export CSV
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-1 small" href="#">
                                        📄 Export PDF
                                    </a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>

                {{-- Status Tabs (visible on desktop) --}}
                <div class="border-top d-none d-md-block">
                    <ul class="nav nav-pills gap-4 px-3 py-2" id="taskStatusTabs">
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold py-1" data-status="all" href="#">
                                All Tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold py-1" data-status="Assign To Tailor" href="#">
                                Assign To Tailor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold py-1" data-status="In Progress" href="#">
                                In Progress
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold py-1" data-status="Completed" href="#">
                                Completed
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Search row --}}
                <div class="border-top">
                    {{-- <div class="px-3 py-2">
                        <form method="GET" action="{{ route('tasks.index') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div style="width:300px">
                                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-sm" placeholder="Search tasks by title, project..">
                                </div>

                                <form id="perPageForm" method="GET" action="{{ route('tasks.index') }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <input type="hidden" name="per_page" id="per_page_value" value="{{ $perPage ?? 10 }}">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" style="min-width:80px">
                                            {{ $perPage ?? 10 }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item perPageItem" data-value="10">10</a></li>
                                            <li><a class="dropdown-item perPageItem" data-value="25">25</a></li>
                                            <li><a class="dropdown-item perPageItem" data-value="50">50</a></li>
                                            <li><a class="dropdown-item perPageItem" data-value="100">100</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </form>
                    </div> --}}
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Task Name</th>
                                <th>Project</th>
                                <th>Assign To</th>
                                <th>Due Date</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr data-status="{{ $task->status }}">
                                    <td class="ps-3">{{ $task->id }}</td>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        {{-- @if($task->description)
                                            <br>
                                            <small class="text-muted" title="{{ $task->description }}">
                                                {{ \Illuminate\Support\Str::words($task->description, 3, '...') }}
                                            </small>
                                        @endif --}}
                                    </td>
                                    <td>{{ $task->project->project_name ?? '-' }}</td>
<td>
    @php $authUser = auth()->user(); @endphp

    @if ($authUser->hasRole('admin'))

        @foreach ($task->assignedUsers as $u)
            {{ $u->name }} ({{ $u->getRoleNames()->first() }})@if(!$loop->last), @endif
        @endforeach

    @else

        @php
            $me = $task->assignedUsers->where('id', $authUser->id)->first();
        @endphp

        {{ $me ? $me->name.' ('.$me->getRoleNames()->first().')' : '-' }}

    @endif
</td>
                                    <td>
                                        {{ $task->due_date }}
                                        {{-- @if($task->due_time)
                                            <br><small class="text-muted">{{ $task->due_time }}</small>
                                        @endif --}}
                                    </td>
                                      <td class="text-center">
                                        <div class="rounded-pill px-3 py-1 d-inline-block fw-semibold
                                            {{ $task->priority == 'High' ? 'bg-danger text-white' : '' }}
                                            {{ $task->priority == 'Medium' ? 'bg-warning text-dark' : '' }}
                                            {{ $task->priority == 'Low' ? 'bg-success text-white' : '' }}">
                                            {{ $task->priority }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($task->requested_status)
                                            <div class="mb-1">
                                                <span class="badge bg-warning text-dark">
                                                    Pending: {{ $task->requested_status }}
                                                </span>
                                            </div>
                                        @endif
                                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                <option value="Assign To Tailor" {{ $task->status == 'Assign To Tailor' ? 'selected' : '' }}>Assign To Tailor</option>
                                                <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end pe-3">
                                        @if ($task->project_id)
                                            <a href="{{ route('projects.measurements.pdf', $task->project_id) }}" class="text-decoration-none text-secondary me-2" title="Download measurements">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif

                                        @if (auth()->user()->hasRole('admin') && $task->requested_status)
                                            <form action="{{ route('tasks.approve', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn p-0 border-0 bg-transparent text-secondary me-2" title="Approve">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="#" class="text-decoration-none text-secondary me-2 edit-btn" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#addTaskModal"
                                            data-id="{{ $task->id }}" data-title="{{ $task->title }}"
                                            data-project="{{ $task->project_id }}" data-date="{{ $task->due_date }}"
                                            data-time="{{ $task->due_time }}" data-priority="{{ $task->priority }}"
                                            data-status="{{ $task->status }}" data-description="{{ $task->description }}"
                                            data-assigned='{{ json_encode($task->assignedUsers->map(fn($u) => ["id" => $u->id, "name" => $u->name, "role" => $u->getRoleNames()->first()])) }}'>
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn p-0 border-0 bg-transparent text-secondary" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No tasks found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($tasks->hasPages())
                    <div class="border-top d-flex justify-content-end align-items-center gap-4 px-3 py-2">
                        <div class="text-muted small">
                            {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }}
                        </div>
                        <div>
                            {{ $tasks->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Success Message --}}
@if (session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

{{-- ================= ADD/EDIT TASK MODAL ================= --}}
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="taskForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod">

                <div class="modal-header">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Task Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Project</label>
                            <select name="project_id" id="project_id" class="form-control">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Assign To</label>
                            <div id="assignRows"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Due Time</label>
                            <input type="text" id="due_time" name="due_time" class="form-control" placeholder="Select Time">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select name="priority" id="priority" class="form-control">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="Assign To Tailor">Assign To Tailor</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-green">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts and Styles --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Pass data to JavaScript
window.rolesData = @json($roles->pluck('name'));
window.csrfToken = "{{ csrf_token() }}";
window.getUsersUrl = "{{ route('get.users.by.roles') }}";
window.storeTaskUrl = "{{ route('tasks.store') }}";

document.addEventListener("DOMContentLoaded", function() {
    // Initialize flatpickr
    const modal = document.getElementById('addTaskModal');
    modal.addEventListener('shown.bs.modal', function() {
        if (!document.querySelector("#due_time")._flatpickr) {
            flatpickr("#due_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                time_24hr: false,
                minuteIncrement: 1,
                defaultDate: new Date()
            });
        }
        setTimeout(() => {
            initializeSelect2();
        }, 100);
    });

    // Tab filtering
    const tabs = document.querySelectorAll("#taskStatusTabs .nav-link");
    const dropdownItems = document.querySelectorAll("#taskStatusDropdown .dropdown-item");
    const rows = document.querySelectorAll("tbody tr[data-status]");

    function filterTasks(status) {
        rows.forEach(row => {
            if (status === "all") {
                row.style.display = "";
            } else {
                row.style.display = row.dataset.status === status ? "" : "none";
            }
        });
    }

    tabs.forEach(tab => {
        tab.addEventListener("click", function(e) {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");
            filterTasks(this.dataset.status);
        });
    });

    dropdownItems.forEach(item => {
        item.addEventListener("click", function(e) {
            e.preventDefault();
            dropdownItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");
            filterTasks(this.dataset.status);
        });
    });

    // Initialize with one empty row
    addNewRow();

    // Reset modal on close
    document.getElementById('addTaskModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('taskForm').reset();
        document.getElementById('taskForm').action = window.storeTaskUrl;
        document.getElementById('formMethod').value = "";
        document.querySelector('.modal-title').innerText = "Add Task";

        // Destroy all Select2 instances
        $('.role-select, .user-select').select2('destroy');

        // Reset assign rows
        document.getElementById('assignRows').innerHTML = '';
        addNewRow();
    });

    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelector('.modal-title').innerText = "Edit Task";
            const form = document.getElementById('taskForm');
            form.action = "/tasks/" + this.dataset.id;
            document.getElementById('formMethod').value = "PUT";

            // Basic Fields
            document.getElementById('title').value = this.dataset.title || '';
            document.getElementById('project_id').value = this.dataset.project || '';
            document.getElementById('due_date').value = this.dataset.date || '';
            document.getElementById('due_time').value = this.dataset.time || '';
            document.getElementById('priority').value = this.dataset.priority || 'Low';
            document.getElementById('status').value = this.dataset.status || 'Assign To Tailor';
            document.getElementById('description').value = this.dataset.description || '';

            // Destroy all Select2 instances before resetting rows
            $('.role-select, .user-select').select2('destroy');

            // Assigned Users
            try {
                const assignedUsers = JSON.parse(this.dataset.assigned || '[]');
                const assignRows = document.getElementById('assignRows');
                assignRows.innerHTML = '';

                if (assignedUsers && assignedUsers.length > 0) {
                    assignedUsers.forEach((user, index) => {
                        addNewRowWithData(index, user);
                    });
                } else {
                    addNewRow();
                }
            } catch (e) {
                console.error('Error parsing assigned users:', e);
                addNewRow();
            }
        });
    });

    // Per page dropdown handler
    document.querySelectorAll('.perPageItem').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('per_page_value').value = this.dataset.value;
            document.getElementById('perPageForm').submit();
        });
    });
});

// Global counter for row indices
let rowCounter = 0;

function initializeSelect2() {
    $('.role-select').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Select Role',
        allowClear: true,
        dropdownParent: $('#addTaskModal .modal-content')
    });

    $('.user-select').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Select User',
        allowClear: true,
        dropdownParent: $('#addTaskModal .modal-content')
    });
}

function addNewRow() {
    const assignRows = document.getElementById('assignRows');
    const index = rowCounter;
    const rowHtml = createAssignRow(index);
    assignRows.insertAdjacentHTML('beforeend', rowHtml);

    $(`#role-select-${index}, #user-select-${index}`).select2({
        theme: 'default',
        width: '100%',
        placeholder: $(`#role-select-${index}`).hasClass('role-select') ? 'Select Role' : 'Select User',
        allowClear: true,
        dropdownParent: $('#addTaskModal .modal-content')
    });

    const newRow = assignRows.lastElementChild;
    bindRoleChange(newRow, index);
    rowCounter++;
}

function addNewRowWithData(index, user) {
    const assignRows = document.getElementById('assignRows');
    const rowHtml = createAssignRowWithData(index, user);
    assignRows.insertAdjacentHTML('beforeend', rowHtml);

    $(`#role-select-${index}, #user-select-${index}`).select2({
        theme: 'default',
        width: '100%',
        placeholder: $(`#role-select-${index}`).hasClass('role-select') ? 'Select Role' : 'Select User',
        allowClear: true,
        dropdownParent: $('#addTaskModal .modal-content')
    });

    const newRow = assignRows.lastElementChild;
    bindRoleChange(newRow, index);

    if (index >= rowCounter) {
        rowCounter = index + 1;
    }

    if (user && user.role) {
        setTimeout(() => {
            $(`#role-select-${index}`).val(user.role).trigger('change');
            setTimeout(() => {
                if (user.id) {
                    $(`#user-select-${index}`).val(user.id).trigger('change');
                }
            }, 500);
        }, 200);
    }
}

function createAssignRow(index) {
    let roleOptions = '<option></option>';
    if (window.rolesData && window.rolesData.length > 0) {
        window.rolesData.forEach(role => {
            const displayName = role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            roleOptions += `<option value="${role}">${displayName}</option>`;
        });
    }

    return `
        <div class="row g-2 align-items-center assign-row mb-2" id="assign-row-${index}">
            <div class="col-md-5">
                <select name="assignments[${index}][role]" class="form-select role-select" id="role-select-${index}" style="width: 100%;">
                    ${roleOptions}
                </select>
            </div>
            <div class="col-md-5">
                <select name="assignments[${index}][user_id]" class="form-select user-select" id="user-select-${index}" style="width: 100%;">
                    <option></option>
                </select>
            </div>
            <div class="col-md-2 text-center d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-sm p-0 border-0 bg-transparent add-row-btn" onclick="addNewRow()" title="Add new row">
                    <i class="bi bi-plus-circle-fill text-secondary"></i>
                </button>
                <button type="button" class="btn btn-sm p-0 border-0 bg-transparent remove-row-btn" onclick="removeRow(${index})" title="Remove row">
                    <i class="bi bi-x-circle-fill text-secondary"></i>
                </button>
            </div>
        </div>
    `;
}

function createAssignRowWithData(index, user) {
    let roleOptions = '<option></option>';
    if (window.rolesData && window.rolesData.length > 0) {
        window.rolesData.forEach(role => {
            const selected = (user && user.role === role) ? 'selected' : '';
            const displayName = role.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            roleOptions += `<option value="${role}" ${selected}>${displayName}</option>`;
        });
    }

    return `
        <div class="row g-2 align-items-center assign-row mb-2" id="assign-row-${index}">
            <div class="col-md-5">
                <select name="assignments[${index}][role]" class="form-select role-select" id="role-select-${index}" style="width: 100%;">
                    ${roleOptions}
                </select>
            </div>
            <div class="col-md-5">
                <select name="assignments[${index}][user_id]" class="form-select user-select" id="user-select-${index}" style="width: 100%;">
                    <option></option>
                    ${user ? `<option value="${user.id}" selected>${user.name}</option>` : ''}
                </select>
            </div>
            <div class="col-md-2 text-center d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-sm p-0 border-0 bg-transparent add-row-btn" onclick="addNewRow()" title="Add new row">
                    <i class="bi bi-plus-circle-fill text-secondary"></i>
                </button>
                <button type="button" class="btn btn-sm p-0 border-0 bg-transparent remove-row-btn" onclick="removeRow(${index})" title="Remove row">
                    <i class="bi bi-x-circle-fill text-secondary"></i>
                </button>
            </div>
        </div>
    `;
}

function bindRoleChange(row, index) {
    const roleSelect = $(`#role-select-${index}`);
    const userSelect = $(`#user-select-${index}`);

    roleSelect.on('change', function() {
        const role = this.value;
        if (!role) {
            userSelect.empty().append('<option></option>').trigger('change');
            return;
        }

        userSelect.empty().append('<option value="">Loading...</option>').trigger('change');

        fetch(window.getUsersUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ roles: [role] })
        })
        .then(response => response.json())
        .then(users => {
            userSelect.empty().append('<option></option>');
            if (users && users.length > 0) {
                users.forEach(user => {
                    const option = new Option(user.name, user.id, false, false);
                    userSelect.append(option);
                });
            } else {
                userSelect.append('<option value="" disabled>No users found</option>');
            }
            userSelect.trigger('change');
        })
        .catch(error => {
            console.error('Error fetching users:', error);
            userSelect.empty().append('<option value="">Error loading users</option>').trigger('change');
        });
    });
}

function removeRow(index) {
    const rows = document.querySelectorAll('.assign-row');
    if (rows.length > 1) {
        $(`#role-select-${index}`).select2('destroy');
        $(`#user-select-${index}`).select2('destroy');
        const row = document.getElementById(`assign-row-${index}`);
        if (row) {
            row.remove();
        }
    } else {
        alert('At least one assignment is required');
    }
}
</script>

<style>
/* Select2 Styling */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
    padding-left: 12px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

/* Table styling to match products page */
.table th {
    font-weight: 500;
    color: #6c757d;
    border-bottom-width: 1px;
}

.table td {
    vertical-align: middle;
}

/* Badge styling */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

/* Status tabs styling */
.nav-pills .nav-link {
    color: #6c757d;
    background: transparent;
    border-radius: 0;
    padding: 0.25rem 0;
}

.nav-pills .nav-link.active {
    color: #000;
    background: transparent;
    border-bottom: 2px solid #000;
}

/* Action buttons */
.text-secondary {
    color: #6c757d !important;
}

.text-secondary:hover {
    color: #495057 !important;
}

/* Modal styling */
.modal-content {
    border: none;
    border-radius: 0.5rem;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

/* Form controls */
.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Assign row buttons */
.add-row-btn, .remove-row-btn {
    font-size: 1.1rem;
}

.add-row-btn:hover i, .remove-row-btn:hover i {
    opacity: 0.7;
}

.assign-row:only-child .remove-row-btn {
    display: none;
}

/* Alert positioning */
.position-fixed {
    z-index: 1050;
}
</style>
@endsection
