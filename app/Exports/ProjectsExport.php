<?php
namespace App\Exports;

use App\Services\ProjectQueryService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ProjectsExport implements FromCollection, WithHeadings
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection(): Collection
    {
        $projects = app(ProjectQueryService::class)
            ->list($this->search)
            ->get();

        return $projects->map(function ($project) {
            return [
                'Project Name' => $project->project_name,
                'Customer'     => $project->customer->name ?? '-',
                'Total Amount' => number_format($project->total_amount, 2),
                'Received'     => number_format($project->received_amount, 2),
                'Remaining'    => number_format(max(0, $project->remaining_amount), 2),
                'Status'       => $project->status,
                'Date'         => $project->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Project Name',
            'Customer',
            'Total Amount',
            'Received',
            'Remaining',
            'Status',
            'Date'
        ];
    }
}
