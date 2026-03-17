<!-- resources/views/pdf/export_projects.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Projects PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h3 { text-align: center; margin-bottom: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h3>Projects List</h3>
    <table>
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Received</th>
                <th>Remaining</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->project_name }}</td>
                    <td>{{ $project->customer?->name ?? '-' }}</td>
                    <td>₹ {{ number_format($project->total_amount ?? 0, 2) }}</td>
                    <td>₹ {{ number_format($project->received_amount ?? 0, 2) }}</td>
                    <td>₹ {{ number_format($project->remaining_amount ?? 0, 2) }}</td>
                    <td>{{ $project->task_status ?? $project->status }}</td>
                    <td>{{ $project->created_at->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No projects found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
