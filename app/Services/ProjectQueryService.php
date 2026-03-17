<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectQueryService
{
    public function list($search = null)
    {
        // ===============================
        // Latest Task Per Project
        // ===============================
        $latestTasks = DB::table('tasks as t1')
            ->select('t1.project_id', 't1.status')
            ->whereRaw('t1.created_at = (
                SELECT MAX(t2.created_at)
                FROM tasks t2
                WHERE t2.project_id = t1.project_id
            )');

        // ===============================
        // Latest Quotation Per Project
        // ===============================
        $latestQuotation = DB::table('quotations as q1')
            ->select('q1.project_id', 'q1.grand_total')
            ->whereRaw('q1.version = (
                SELECT MAX(q2.version)
                FROM quotations q2
                WHERE q2.project_id = q1.project_id
            )');

        // ===============================
        // Main Query
        // ===============================
        return Project::with('customer')

            ->leftJoin('received_payments', 'projects.id', '=', 'received_payments.project_id')

            ->leftJoinSub($latestQuotation, 'latest_quotation', function ($join) {
                $join->on('projects.id', '=', 'latest_quotation.project_id');
            })

            ->leftJoinSub($latestTasks, 'latest_tasks', function ($join) {
                $join->on('projects.id', '=', 'latest_tasks.project_id');
            })

            // =========================================
            // 🔥 ROLE BASED FILTERING (IMPORTANT PART)
            // =========================================
            ->when(auth()->check() && auth()->user()->hasRole('sales_associates'), function ($q) {
                $q->where('projects.sales_associate_id', auth()->id());
            })

            // =========================================
            // SELECT FIELDS
            // =========================================
            ->select(
                'projects.*',

                DB::raw('COALESCE(latest_quotation.grand_total,0) as total_amount'),

                DB::raw('COALESCE(SUM(received_payments.amount),0) as received_amount'),

                DB::raw(
                    '(COALESCE(latest_quotation.grand_total,0)
                     - COALESCE(SUM(received_payments.amount),0)) as remaining_amount'
                ),

                'latest_tasks.status as task_status'
            )

            // =========================================
            // SEARCH FILTER
            // =========================================
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('projects.project_name', 'like', "%{$search}%")
                       ->orWhereHas('customer', function ($q2) use ($search) {
                           $q2->where('name', 'like', "%{$search}%");
                       });
                });
            })

            // =========================================
            // GROUP BY (IMPORTANT)
            // =========================================
            ->groupBy(
                'projects.id',
                'latest_quotation.grand_total',
                'latest_tasks.status'
            )

            ->orderBy('projects.created_at', 'desc');
    }
}
