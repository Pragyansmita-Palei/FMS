<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $newOrders = Project::where('status', 'confirmed')->count();
        $inProgressOrders = Project::where('status', 'in_production')->count();
        $completedOrders = Project::where('status', 'delivered')->count();

        $recentActivities = Project::latest()->take(10)->get();
$tasks = Task::latest()->get(); // get all
$recentTasks = $tasks->take(3); // only 3 for dashboard

        $totalAmount     = DB::table('quotation_items')->sum('total');
        $receivedAmount  = DB::table('received_payments')->sum('amount');
        $dueAmount       = DB::table('projects')->sum('due_amount');
        $totalProjects   = DB::table('projects')->count();

         $totalCustomer = DB::table('customers')->count();
         $totalSales    = DB::table('sales_associates')->count();
         $totalTailor   = DB::table('tailors')->count();
         $totalQuotation = DB::table('quotation_items')->distinct('project_id')->count('project_id');

         $pending   = DB::table('projects')->where('status', 'pending')->count();
         $cancelled = DB::table('projects')->where('status', 'cancelled')->count();
         $confirmed = DB::table('projects')->where('status', 'confirmed')->count();
         $delivered = DB::table('projects')->where('status', 'delivered')->count();
        $projects = Project::latest()->get();

        $completed     = Project::where('status', 'delivered')->count();
$inProduction  = Project::where('status', 'in_production')->count();
$pendingCount  = Project::where('status', 'pending')->count();

$total = $completed + $inProduction + $pendingCount;

$completedPercent = $total > 0 ? round(($completed / $total) * 100) : 0;
$progressPercent  = $total > 0 ? round(($inProduction / $total) * 100) : 0;
$pendingPercent   = $total > 0 ? round(($pendingCount / $total) * 100) : 0;
        return view('dashboard.index', compact(
            'newOrders',
            'inProgressOrders',
            'completedOrders',
            'recentActivities',
            'recentTasks',
            'totalAmount',
            'receivedAmount',
            'dueAmount',
            'totalProjects',
            'totalCustomer',
            'totalSales',
            'totalTailor',
           'totalQuotation',
             'pending',
            'cancelled',
            'confirmed',
             'delivered',
             'projects',
             'completedPercent',
    'progressPercent',
    'pendingPercent',
    'completed',
    'inProduction',
    'pendingCount',
      'tasks',

        ));
    }


    // ✅ AJAX filter for Payments chart
    public function filterPayments()
    {
        $filter = request('filter');

        $from = null;
        $to   = null;

        if ($filter === 'this_month') {
            $from = Carbon::now()->startOfMonth();
            $to   = Carbon::now()->endOfMonth();
        }

        if ($filter === 'last_month') {
            $from = Carbon::now()->subMonth()->startOfMonth();
            $to   = Carbon::now()->subMonth()->endOfMonth();
        }

        if ($filter === 'this_fy') {

            $year = Carbon::now()->month >= 4
                ? Carbon::now()->year
                : Carbon::now()->year - 1;

            $from = Carbon::create($year, 4, 1)->startOfDay();
            $to   = $from->copy()->addYear()->subDay();
        }

        if ($filter === 'last_fy') {

            $year = Carbon::now()->month >= 4
                ? Carbon::now()->year - 1
                : Carbon::now()->year - 2;

            $from = Carbon::create($year, 4, 1)->startOfDay();
            $to   = $from->copy()->addYear()->subDay();
        }

        if ($filter === 'custom') {
            $from = Carbon::parse(request('from'))->startOfDay();
            $to   = Carbon::parse(request('to'))->endOfDay();
        }

        $quotationQuery = DB::table('quotation_items');
        $receivedQuery  = DB::table('received_payments');
        $projectsQuery  = DB::table('projects');

        if ($filter !== 'all') {
            $quotationQuery->whereBetween('created_at', [$from, $to]);
            $receivedQuery->whereBetween('created_at', [$from, $to]);
            $projectsQuery->whereBetween('created_at', [$from, $to]);
        }

        return response()->json([
            'totalAmount'    => $quotationQuery->sum('total'),
            'receivedAmount' => $receivedQuery->sum('amount'),
            'dueAmount'      => $projectsQuery->sum('due_amount'),
        ]);
    }




public function getDailyProjectStats()
{
    $days = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

    $stats = [];
    $totalProjects = 0;
    $maxProjects = 0;

    $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);

    for ($i = 0; $i < 7; $i++) {

        $date = $startOfWeek->copy()->addDays($i);

        $count = Project::whereDate('created_at', $date)->count();

        $stats[$i] = [
            'day' => $days[$i],
            'count' => $count,
            'date' => $date->format('Y-m-d')
        ];

        $totalProjects += $count;

        if ($count > $maxProjects) {
            $maxProjects = $count;
        }
    }

    foreach ($stats as &$stat) {

        // percentage of weekly projects
        $stat['percentage'] = $totalProjects > 0
            ? round(($stat['count'] / $totalProjects) * 100)
            : 0;

        // dynamic bar height (minimum 25px so empty day still visible)
        if ($maxProjects > 0) {
            $height = round(($stat['count'] / $maxProjects) * 120);
            $stat['height'] = max($height, 25);
        } else {
            $stat['height'] = 25;
        }
    }

    return response()->json($stats);
}
}




