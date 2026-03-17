@extends('layouts.app')
@section('title', 'Dashboard | FurnishPro')

@section('content')



    <div class="dashboard-wrapper">

        <!-- ================= HEADER ================= -->
        <div class="d-flex justify-content-between align-items-center mb-4 dashboard-header">

            <div>
                <h3 class="mb-1">Dashboard</h3>
                <div class="dashboard-sub">
                    Plan, prioritize, and accomplish your tasks with ease.
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('projects.start') }}" class="btn-green text-decoration-none">
                    + Add Project
                </a> <!-- <button class="btn-outline-custom">Import Data</button> -->
            </div>

        </div>

        <!-- ================= TOP STATS ================= -->
        <div class="row g-4">

            <!-- Total -->
            <div class="col-md-3">
                <div class="dash-card stat-green">
                    <div class="stat-title">Total Projects</div>
                    <div class="stat-number">{{ $totalProjects }}</div>
                    <div class="stat-sub">All projects</div>
                </div>
            </div>

            <!-- Ended = completed (status = delivered) -->
            <div class="col-md-3">
                <div class="dash-card">
                    <div class="title">Ended Projects</div>
                    <div class="number">{{ $completedOrders }}</div>
                    <div class="stat-sub text-success">Completed</div>
                </div>
            </div>

            <!-- Running = in production -->
            <div class="col-md-3">
                <div class="dash-card">
                    <div class="title">Running Projects</div>
                    <div class="number">{{ $inProgressOrders }}</div>
                    <div class="stat-sub text-success">In Production</div>
                </div>
            </div>

            <!-- Pending -->
            <div class="col-md-3">
                <div class="dash-card">
                    <div class="title">Pending Projects</div>
                    <div class="number">{{ $pending }}</div>
                    <div class="stat-sub text-muted">On Discuss</div>
                </div>
            </div>

        </div>

        <!-- ================= SECOND ROW ================= -->
        <div class="row mt-3 g-3">

            <!-- resources/views/components/project-analytics.blade.php -->
            <div class="col-md-7">
                <div class="dash-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Project Analytics</h6>
                    </div>

                    <!-- Analytics Bars - EXACT MATCH to Dribbble image -->
                    <div class="analytics-bars"></div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="dash-card projects h-100">

                    <div class="row align-items-center mb-3">
                        <div class="col-6">
                            <h6 class="mb-0">Tasks</h6>
                        </div>

                     <div class="col-6 text-end">
    @if ($tasks->count() > 3)
        <a href="{{ route('tasks.index') }}" class="btn-green text-decoration-none">
            View All
        </a>
    @endif
</div>
                    </div>

                    <div class="recent-activities">

                        @forelse($recentTasks as $task)
                            <div class="activity-item">

                                <div class="activity-details">
                                    <span class="project-id">
                                        {{ $task->title }}
                                    </span>

                                    <span class="customer-name">
                                        | {{ ucfirst($task->priority) }} Priority
                                        | {{ ucfirst($task->status) }}
                                    </span>
                                </div>

                                <div class="activity-footer">
                                    <span class="activity-type">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, h:i A') }}
                                    </span>

                                    <a href="javascript:void(0)"
   class="view-project viewTaskBtn"
   data-bs-toggle="modal"
   data-bs-target="#viewTaskModal"
   data-title="{{ $task->title }}"
   data-priority="{{ ucfirst($task->priority) }}"
   data-status="{{ ucfirst($task->status) }}"
   data-date="{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}"
   data-time="{{ \Carbon\Carbon::parse($task->due_date)->format('h:i A') }}"
   data-description="{{ $task->description }}">
    View Task
</a>
                                </div>

                            </div>

                        @empty
                            <p class="text-muted">No tasks found</p>
                        @endforelse

                    </div>

                </div>
            </div>
            <!-- View Task Modal -->
<div class="modal fade"
     id="viewTaskModal"
     tabindex="-1">

    <div class="modal-dialog custom-modal modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row gy-3">

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Title</div>
                        <div id="taskTitle"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Priority</div>
                        <div id="taskPriority"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Status</div>
                        <div id="taskStatus"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Due Date</div>
                        <div id="taskDate"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="fw-semibold text-muted">Due Time</div>
                        <div id="taskTime"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="fw-semibold text-muted">Description</div>
                        <div id="taskDescription"></div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
            <!-- ================= THIRD ROW ================= -->
            <div class="row mt-3 g-3">
                <div class="col-md-6">
                    <div class="dash-card projects">
                        <div class="row align-items-center mb-3">
                            <div class="col-6">
                                <h6 class="mb-0">Recent Activities</h6>
                            </div>

                            <div class="col-6 text-end">
                                @if ($projects->count() > 3)
                                    <a href="{{ route('projects.index') }}" class="btn-green text-decoration-none">
                                        View All
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="recent-activities">
                            @forelse($projects->take(3) as $project)
                                <div class="activity-item">

                                    <div class="activity-details">
                                        <span class="project-id">
                                            Project Id: {{ $project->project_id ?? 'PROJ' . $project->id }}
                                        </span>
                                        <span class="customer-name">
                                            | Customer Name: {{ $project->customer->name ?? 'Customer' }}
                                        </span>
                                    </div>

                                    <div class="activity-footer">
                                        <span class="activity-type">Projects Created</span>

                                        <a href="{{ route('projects.create', ['step' => 0, 'project_id' => $project->id]) }}"
                                            class="view-project text-decoration-none">
                                            View Project →
                                        </a>
                                    </div>

                                </div>
                            @empty
                                <p class="text-muted">No projects found</p>
                            @endforelse
                        </div>



                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dash-card text-center">
                        <h6>Project Progress</h6>

                        <div class="progress-wrapper">

                            <div class="semi-steps" id="projectProgress" data-done="{{ $completedPercent }}"
                                data-progress="{{ $progressPercent }}" data-pending="{{ $pendingPercent }}"
                                style="
        --done: {{ $completedPercent }};
        --progress: {{ $progressPercent }};
        --pending: {{ $pendingPercent }};
     ">

                                <div class="center-text">
                                    <strong id="progressText">{{ $completedPercent }}%</strong>
                                    <small id="progressLabel">Completed</small>
                                </div>
                            </div>
                        </div>

                        {{-- <small class="text-muted d-block mb-3">Project Ended</small> --}}
                        <div class="progress-legend d-flex justify-content-center gap-3 mt-3">
                            <div class="legend-item">
                                <span class="legend-color completed"></span> Completed
                            </div>
                            <div class="legend-item">
                                <span class="legend-color in-progress"></span> In Progress
                            </div>
                            <div class="legend-item">
                                <span class="legend-color pending"></span> Pending
                            </div>
                        </div>
                    </div>

                </div>
            </div>



        </div>

    </div>


    <script>
document.addEventListener("DOMContentLoaded", function () {

    const buttons = document.querySelectorAll(".viewTaskBtn");

    buttons.forEach(button => {
        button.addEventListener("click", function () {

            document.getElementById("taskTitle").innerText = this.dataset.title;
            document.getElementById("taskPriority").innerText = this.dataset.priority;
            document.getElementById("taskStatus").innerText = this.dataset.status;
            document.getElementById("taskDate").innerText = this.dataset.date;
            document.getElementById("taskTime").innerText = this.dataset.time;
            document.getElementById("taskDescription").innerText = this.dataset.description;

        });
    });

});
</script>
@endsection
