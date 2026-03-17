<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ReceivedPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectReceivedPaymentController extends Controller
{
    // List payments for a project
    public function index(Project $project)
    {
        $payments = ReceivedPayment::with('createdBy')
            ->where('project_id', $project->id)
            ->latest()
            ->paginate(10);

        $dueAmount = $project->due_amount;

        return view('projects.received-payments', compact(
            'project',
            'payments',
            'dueAmount'
        ));
    }

    // Store a new payment
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'payment_mode' => 'required',
            'amount' => 'required|numeric|min:1',
            'transaction_number' => [
                'required_unless:payment_mode,Cash',
                'nullable',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->payment_mode !== 'Cash' && $value) {
                        $exists = ReceivedPayment::where('transaction_number', $value)
                            ->exists();
                        if ($exists) {
                            $fail('The transaction number has already been used.');
                        }
                    }
                },
            ],
            'payment_date' => 'required|date',
        ]);

        ReceivedPayment::create([
            'project_id'       => $project->id,
            'order_id'         => $project->order_id,
            'amount'           => $request->amount,
            'payment_mode'     => $request->payment_mode,
            'transaction_number' => $request->payment_mode === 'Cash'
                                    ? null
                                    : $request->transaction_number,
            'payment_date'     => $request->payment_date,
            'remarks'          => $request->remarks,
            'created_by'       => Auth::id()
        ]);

        $this->updateProjectDueAmount($project->id);

        return back()->with('success','Payment added successfully');
    }

    // Update an existing payment
    public function update(Request $request, Project $project, ReceivedPayment $payment)
    {
        $request->validate([
            'payment_mode' => 'required',
            'amount' => 'required|numeric|min:1',
            'transaction_number' => [
                'required_unless:payment_mode,Cash',
                'nullable',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($request, $payment) {
                    if ($request->payment_mode !== 'Cash' && $value) {
                        $exists = ReceivedPayment::where('transaction_number', $value)
                            ->where('id', '!=', $payment->id)
                            ->exists();
                        if ($exists) {
                            $fail('The transaction number has already been used.');
                        }
                    }
                },
            ],
            'payment_date' => 'required|date',
        ]);

        if ($payment->project_id != $project->id) {
            abort(403);
        }

        $payment->update([
            'payment_mode'     => $request->payment_mode,
            'amount'           => $request->amount,
            'transaction_number' => $request->payment_mode === 'Cash'
                                    ? null
                                    : $request->transaction_number,
            'payment_date'     => $request->payment_date,
            'remarks'          => $request->remarks,
        ]);

        $this->updateProjectDueAmount($project->id);

        return back()->with('success','Payment updated successfully');
    }

    // Delete a payment
    public function destroy(Project $project, ReceivedPayment $payment)
    {
        if ($payment->project_id != $project->id) {
            abort(403);
        }

        $payment->delete();
        $this->updateProjectDueAmount($project->id);

        return back()->with('success','Payment deleted successfully');
    }

    // Update project's due amount
    private function updateProjectDueAmount($projectId)
    {
        $project = Project::findOrFail($projectId);

        $totalPaid = ReceivedPayment::where('project_id', $projectId)
                        ->sum('amount');

        $dueAmount = $project->final_amount - $totalPaid;

        $project->update([
            'due_amount' => $dueAmount
        ]);
    }

    // List payment details for all projects
    public function paymentDetails()
    {
        $projects = Project::with(['payments.createdBy'])->orderBy('name')->get();

        $completedProjects = $projects->filter(fn($proj) => $proj->status === 'completed');
        $remainingProjects = $projects->filter(fn($proj) => $proj->status !== 'completed');

        return view('projects.payment-details', [
            'projects' => $projects,
            'completedProjects' => $completedProjects,
            'remainingProjects' => $remainingProjects,
        ]);
    }
}