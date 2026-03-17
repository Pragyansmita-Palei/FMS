<div class="overview-container">
    <div class="row g-3 g-md-4">

        {{-- ================= CLIENT INFORMATION ================= --}}
        <div class="col-12 col-md-6">
            <div class="card overview-card p-3 rounded-4 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="client-icon me-3">
                        <i class="bi bi-person-circle fs-4 text-primary"></i>
                    </div>
                    <h6 class="card-title mb-0 fw-semibold">Client Information</h6>
                </div>

                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-person me-2 text-muted"></i>
                            <span>Name</span>
                        </div>
                        <div class="info-value fw-medium">
                            {{ $project->customer->name ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-telephone me-2 text-muted"></i>
                            <span>Phone</span>
                        </div>
                        <div class="info-value fw-medium">
                            {{ $project->customer->phone ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-calendar me-2 text-muted"></i>
                            <span>Project Date</span>
                        </div>
                        <div class="info-value fw-medium">
                            {{ $project->created_at->format('M d, Y') ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">
                            <i class="bi bi-hash me-2 text-muted"></i>
                            <span>Project ID</span>
                        </div>
                        <div class="info-value fw-medium">
                            #{{ str_pad($project->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= PROJECT STATUS ================= --}}
        <div class="col-12 col-md-6">
            <div class="card overview-card p-3 rounded-4 shadow-sm border-0 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="status-icon me-3">
                            <i class="bi bi-clipboard-check fs-4 text-primary"></i>
                        </div>
                        <h6 class="card-title mb-0 fw-semibold">Project Status</h6>
                    </div>
                   <div class="dropdown">
    <button class="btn btn-sm btn-light border dropdown-toggle status-dropdown"
        type="button"
        id="projectStatusDropdown"
        data-bs-toggle="dropdown"
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
                </div>

                <div class="status-timeline">
    <div id="stepConfirmed" class="status-step">
        <div class="step-icon">
            <i class="bi bi-check-circle "></i>
        </div>
        <div class="step-title">Order Confirmed</div>
    </div>

    <div id="stepGoods" class="status-step">
        <div class="step-icon">
            <i class="bi bi-box-seam "></i>
        </div>
        <div class="step-title">Goods Ordered</div>
    </div>

    <div id="stepTailor" class="status-step">
        <div class="step-icon">
            <i class="bi bi-scissors "></i>
        </div>
        <div class="step-title">Sent to Tailor</div>
    </div>

    <div id="stepprogress" class="status-step">
        <div class="step-icon">
            <i class="bi bi-gear "></i>
        </div>
        <div class="step-title">Progress</div>
    </div>

    <div id="stepReady" class="status-step">
        <div class="step-icon">
            <i class="bi bi-bag-check "></i>
        </div>
        <div class="step-title">Order Ready</div>
    </div>

    <div id="stepDispatch" class="status-step">
        <div class="step-icon">
            <i class="bi bi-truck"></i>
        </div>
        <div class="step-title">Dispatch</div>
    </div>

    <div id="stepDelivery" class="status-step">
        <div class="step-icon">
            <i class="bi bi-house-check "></i>
        </div>
        <div class="step-title">Delivery & Installation</div>
    </div>
</div>
            </div>
        </div>

        {{-- ================= TASKS SECTION (LEFT) ================= --}}
        @can('add task')
        <div class="col-12 col-md-6">
            <div id="tasksSection"
                 class="card overview-card p-3 rounded-4 shadow-sm border-0 h-100"
                 style="{{ in_array($project->status, ['confirmed','goods_ordered','in_production']) ? '' : 'display:none;' }}">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="tasks-icon me-3">
                            <i class="bi bi-list-task fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Tasks</h6>
                            <p class="text-muted small mb-0">
                                {{ $project->tasks->count() ?? 0 }} task(s) in progress
                            </p>
                        </div>
                    </div>
                    <button
                        class="btn btn-green btn-sm d-flex align-items-center"
                        id="openTaskModal"
                        data-bs-toggle="modal"
                        data-bs-target="#addTaskModal"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        <span class="d-none d-md-inline">Create Task</span>
                        <span class="d-inline d-md-none">Add</span>
                    </button>
                </div>

                @if($project->tasks && $project->tasks->count())
                    <div class="tasks-container">
                        @foreach($project->tasks as $task)
                        <div class="task-card">
                            <div class="task-header">
                                <h6 class="task-title mb-1">{{ $task->title }}</h6>
                                <div class="task-meta">
                                    <span class="meta-item">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d') : 'No due date' }}
                                    </span>
                                </div>
                            </div>
                            <div class="task-footer d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="task-status">
                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($task->status)) }}">
                                            @if($task->status === 'Completed')
                                                <i class="bi bi-check-circle me-1"></i>
                                            @elseif($task->status === 'In Progress')
                                                <i class="bi bi-arrow-repeat me-1"></i>
                                            @else
                                                <i class="bi bi-circle me-1"></i>
                                            @endif
                                            {{ $task->status }}
                                        </span>
                                    </div>

                                    <div class="task-priority">
                                        <span class="priority-badge priority-{{ strtolower($task->priority) }}">
                                            <i class="bi bi-circle-fill me-1"></i>
                                            {{ $task->priority }}
                                        </span>
                                    </div>
                                </div>

                                <button
                                    class="btn btn-outline-primary btn-sm view-task-btn"
                                    data-task='@json($task)'>
                                    <i class="bi bi-eye me-1"></i> View
                                </button>
                            </div>

                            @if($task->description)
                            <div class="task-description mt-2">
                                <p class="text-muted small mb-0">{{ $task->description }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="empty-state-icon mb-3">
                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                        </div>
                        <h6 class="text-muted mb-2">No Tasks Yet</h6>
                        <p class="text-muted small mb-0">Create your first task to get started</p>
                    </div>
                @endif
            </div>
        </div>
        @endcan

        {{-- ================= ASSIGN INTERIOR SECTION (RIGHT) ================= --}}
        <div class="col-12 col-md-6">
            <div class="card overview-card p-3 rounded-4 shadow-sm border-0 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-house-door fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">Assign Interior</h6>
                            <p class="text-muted small mb-0">
                                Assign this project to an interior designer
                            </p>
                        </div>
                    </div>
                </div>

                <form id="assignInteriorForm">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-3">
                        <label class="form-label">Select Interior</label>
                        <select name="interior_id" id="interior_id" class="form-select">
                            <option value="">Select Interior</option>
                            @foreach($interiors as $interior)
                                <option value="{{ $interior->id }}"
                                    {{ $project->interior_id == $interior->id ? 'selected' : '' }}>
                                    {{ $interior->firm_name }} {{ $interior->name ? '(' . $interior->name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" class="btn btn-green btn-sm w-100" id="assignInteriorBtn">
                        Assign Interior
                    </button>
                </form>

                @if($project->interior)
                <div class="mt-3 p-2 bg-light rounded">
                    <small class="text-muted">Currently Assigned:</small>
                    <div class="fw-semibold">
                        {{ $project->interior->firm_name }} {{ $project->interior->name ? '(' . $project->interior->name . ')' : '' }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ================= MODALS ================= --}}
{{-- View Task Modal --}}
<div class="modal fade" id="viewTaskModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold" id="viewTaskTitle">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Project Name</span>
                        <span id="viewTaskProjectName">-</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Project ID</span>
                        <span id="viewTaskProjectId">-</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Priority</span>
                        <span id="viewTaskPriority">-</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span id="viewTaskStatus">-</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Assignee</span>
                        <span id="viewTaskAssignee">-</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Due Date & Time</span>
                        <span id="viewTaskDue">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-primary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Add Task Modal --}}
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form id="taskForm" action="{{ route('tasks.store') }}" method="POST" class="w-100">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="_method" id="formMethod">

            <div class="modal-content rounded-4 shadow-sm">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Create Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Task Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Stitch sleeves" required>
                            <div class="invalid-feedback" id="title-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign To (Sales)</label>
                            <select name="sales_associate_id" id="sales_associate_id" class="form-select">
                                <option value="">Select Sales</option>
                                @foreach($sales as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign To (Tailor)</label>
                            <select name="tailor_id" id="tailor_id" class="form-select">
                                <option value="">Select Tailor</option>
                                @foreach($tailors as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Time</label>
                            <input type="text" id="due_time" name="due_time" class="form-control" placeholder="Select Time">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" id="priority" class="form-select">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="To Do">To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Save Task
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Toast Notifications --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="taskSuccessToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                Task created successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="taskErrorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-exclamation-circle me-2"></i>
                Failed to create task. Please try again.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

{{-- ================= STYLES ================= --}}
<style>
.overview-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.overview-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.overview-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
}

/* Client Information Styles */
.info-grid {
    display: grid;
    gap: 1rem;
}

.info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    transition: background 0.2s ease;
}

.info-row:hover {
    background: #e9ecef;
}

.info-label {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.875rem;
}

.info-value {
    color: #212529;
    font-size: 0.875rem;
}

/* Status Timeline Styles */
.status-timeline {
    padding-left: 1rem;
    position: relative;
}

.status-timeline::before {
    content: '';
    position: absolute;
    left:35px;
    top: 0.5rem;
    bottom: 0.5rem;
    width: 2px;
    background: #dee2e6;
}

.status-step {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    position: relative;
}

.status-step:last-child {
    margin-bottom: 0;
}

.status-step.active .step-icon {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.step-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
}

.step-content {
    flex: 1;
}

.step-title {
    display: block;
    font-weight: 500;
    color: #212529;
    margin-bottom: 0.25rem;
}

.step-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.status-select {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    border-color: #dee2e6;
}

/* Task Card Styles */
.tasks-container {
    display: grid;
    gap: 0.75rem;
}

.task-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.task-card:hover {
    border-color: #dee2e6;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.task-header {
    margin-bottom: 0.75rem;
}

.task-title {
    color: #212529;
    font-weight: 500;
    line-height: 1.4;
}

.task-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    font-size: 0.75rem;
    color: #6c757d;
}

.meta-item {
    display: flex;
    align-items: center;
}

.task-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f3f5;
}

/* Priority Badges */
.priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.priority-high {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.priority-medium {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.priority-low {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-completed {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.status-in-progress {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.status-to-do {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.task-card {
    animation: fadeInUp 0.3s ease-out;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .overview-card {
        padding: 1rem;
    }

    .info-row {
        padding: 0.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .status-timeline::before {
        left: 1.25rem;
    }

    .step-icon {
        width: 2rem;
        height: 2rem;
        margin-right: 0.75rem;
    }

    .task-meta {
        gap: 0.5rem;
    }

    .task-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .status-select {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }

    #openTaskModal {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
}

@media (max-width: 576px) {
    .status-step {
        margin-bottom: 1rem;
    }

    .step-title {
        font-size: 0.875rem;
    }

    .task-title {
        font-size: 0.875rem;
    }
}

/* Toast positioning for mobile */
@media (max-width: 576px) {
    .position-fixed {
        padding: 0.75rem !important;
    }

    .toast {
        font-size: 0.875rem;
    }
}

/* Loading spinner style */
.spinner-border {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}
</style>

{{-- ================= JAVASCRIPT ================= --}}
<script>

const statusDropdown = document.getElementById('projectStatusDropdown');
const tasksSection = document.getElementById('tasksSection');

function shouldShowTasks(status) {
    return ['confirmed', 'goods_ordered', 'in_production'].includes(status);
}

function updateTimeline(status) {

    const stepsMap = {
        pending: [],
        confirmed: ['stepConfirmed'],
        goods_ordered: ['stepConfirmed', 'stepGoods'],
        assign_to_tailors: ['stepConfirmed', 'stepGoods', 'stepTailor'],
        in_production: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress'],
        order_ready: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress', 'stepReady'],
        dispatch: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress', 'stepReady', 'stepDispatch'],
        delivered: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress', 'stepReady', 'stepDispatch', 'stepDelivery'],
        cancelled: []
    };

    document.querySelectorAll('.status-step').forEach(step => {
        step.classList.remove('active');
    });

    if (stepsMap[status]) {
        stepsMap[status].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('active');
        });
    }
}
document.querySelectorAll('.status-option').forEach(item => {

    item.addEventListener('click', function () {

        const status = this.getAttribute('data-status');
        const label = this.textContent;

        statusDropdown.textContent = label;
        statusDropdown.dataset.status = status;

        fetch("{{ route('projects.updateStatus', $project->id) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ status })
        })
        .then(res => res.json())
        .then(data => {

            if (!data.success) return;

            updateTimeline(status);

            if (tasksSection) {
                tasksSection.style.display = shouldShowTasks(status) ? 'block' : 'none';
            }

            if (shouldShowTasks(status)) {
                setTimeout(() => {
                    tasksSection.scrollIntoView({ behavior: 'smooth' });
                }, 200);
            }

        })
        .catch(err => console.error('Status update error:', err));

    });

});
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addTaskModal');
    modal.addEventListener('shown.bs.modal', function () {
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
    });

    // const statusSelect = document.getElementById('projectStatus');
    // const tasksSection = document.getElementById('tasksSection');
    const currentStatus = "{{ $project->status }}";

    /* =========================
       TASK VISIBILITY RULE
    ========================== */
    function shouldShowTasks(status) {
        return ['confirmed', 'goods_ordered', 'in_production'].includes(status);
    }

    /* =========================
       TIMELINE UPDATE
    ========================== */
//    function updateTimeline(status) {

//     const stepsMap = {
//         pending: [],
//         confirmed: ['stepConfirmed'],
//         goods_ordered: ['stepConfirmed', 'stepGoods'],
//         assign_to_tailors: ['stepConfirmed', 'stepGoods', 'stepTailor'],
//         in_production: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress'],
//         order_ready: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress', 'stepReady'],
//         dispatch: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress', 'stepReady', 'stepDispatch'],
//         delivered: ['stepConfirmed', 'stepGoods', 'stepTailor', 'stepprogress', 'stepReady', 'stepDispatch', 'stepDelivery'],
//         cancelled: []
//     };

//     document.querySelectorAll('.status-step').forEach(step => {
//         step.classList.remove('active');
//     });

//     if (stepsMap[status]) {
//         stepsMap[status].forEach(id => {
//             const el = document.getElementById(id);
//             if (el) el.classList.add('active');
//         });
//     }
// }
    /* =========================
       INITIAL LOAD
    ========================== */
    updateTimeline(currentStatus);
    if (tasksSection) {
        tasksSection.style.display = shouldShowTasks(currentStatus) ? 'block' : 'none';
    }

    /* =========================
       STATUS CHANGE (AJAX)
    ========================== */
    if (statusSelect) {
        statusSelect.addEventListener('change', function () {
            const status = this.value;

            fetch("{{ route('projects.updateStatus', $project->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ status })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;

                updateTimeline(status);
                if (tasksSection) {
                    tasksSection.style.display = shouldShowTasks(status) ? 'block' : 'none';
                }

                if (shouldShowTasks(status)) {
                    setTimeout(() => {
                        tasksSection.scrollIntoView({ behavior: 'smooth' });
                    }, 200);
                }
            })
            .catch(err => console.error('Status update error:', err));
        });
    }
});

// View Task Modal
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-task-btn');
    if (!btn) return;

    const task = JSON.parse(btn.getAttribute('data-task'));

    document.getElementById('viewTaskProjectName').textContent = task.project_name ?? '-';
    document.getElementById('viewTaskProjectId').textContent = task.project_id ? '#' + String(task.project_id).padStart(6, '0') : '-';
    document.getElementById('viewTaskTitle').textContent = task.title ?? '-';
    document.getElementById('viewTaskPriority').textContent = task.priority ?? '-';
    document.getElementById('viewTaskStatus').textContent = task.status ?? '-';

    let assignee = '-';
    if (task.sales_associate && task.sales_associate.name) {
        assignee = task.sales_associate.name;
    } else if (task.tailor && task.tailor.name) {
        assignee = task.tailor.name;
    }
    document.getElementById('viewTaskAssignee').textContent = assignee;

    let due = '-';
    if (task.due_date) {
        let date = new Date(task.due_date);
        let formattedDate = date.toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        due = task.due_time ? formattedDate + ' ' + task.due_time : formattedDate;
    }
    document.getElementById('viewTaskDue').textContent = due;

    const modal = new bootstrap.Modal(document.getElementById('viewTaskModal'));
    modal.show();
});

// Assign Interior
document.getElementById('assignInteriorBtn')?.addEventListener('click', function () {
    const interiorId = document.getElementById('interior_id').value;

    fetch("{{ route('projects.assignInterior', $project->id) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ interior_id: interiorId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => console.error(err));
});
</script>
