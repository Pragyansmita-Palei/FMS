<?php

namespace App\Http\Controllers;
use App\Models\Catalogue;
use App\Models\Product;
use App\Models\QuotationItem;
use App\Models\Store;
use App\Models\Customer;
use App\Models\Project;
use App\Models\SalesAssociate;
use App\Models\Tailor;
use App\Models\Material;
use App\Models\ProductGroup;
use App\Models\Brand;
use App\Models\Area;
use App\Models\Measurement;
use App\Models\User;
use App\Models\ReceivedPayment;

use App\Services\ProjectQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectsExport;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Interior;
use App\Models\TermCondition;

class ProjectController extends Controller
{




    public function index(Request $request, ProjectQueryService $service)
    {
        $search = $request->search;
        $perPage = $request->per_page ?? 10;

        $projects = $service
            ->list($search)
            ->paginate($perPage)
            ->appends($request->query());   // keep filters while paginating

        $interiors = \App\Models\Interior::orderBy('name')->get(); // ✅ add


        return view('projects.index', compact('projects', 'search', 'perPage', 'interiors'));
    }






    // ==============================
    // CREATE PROJECT
    // ==============================
    public function create(Request $request)
    {
        $step = $request->step ?? 1;
        $projectId = $request->project_id ?? session('project_id'); // Prefer request parameter over session

        if ($request->project_id) {
            session(['project_id' => $request->project_id]); // Update session if opened directly
        }

        // STEP access guards
        if ($step == 0 && !$projectId) {
            return redirect()->route('projects.create', ['step' => 1]);
        }

        // ✅ AUTO GENERATE QUOTATION WHEN STEP 4 OPENS
// if ($step == 4 && $projectId) {

        //     $exists = QuotationItem::where('project_id',$projectId)->exists();

        //     if (!$exists) {
//         $this->generateQuotationItems($projectId);
//     }
// }



        if ($step == 5 && !$projectId) {
            return redirect()->route('projects.create', ['step' => 1]);
        }

        // Initialize all variables
        $materials = [];
        $savedMeasurements = [];
        $measurements = collect();
        $materialsByArea = collect();
        $project = null;
        $payments = collect(); // Initialize payments
        $dueAmount = 0; // Initialize due amount

        // Load saved data based on step
        if ($projectId) {
            $project = Project::find($projectId);

            // ✅ Determine completed steps from DB (not session)
            $step1Completed = true;

            $step2Completed = Measurement::where('project_id', $projectId)->exists();

            $step3Completed = Material::where('project_id', $projectId)->exists();

            $step4Completed = QuotationItem::where('project_id', $projectId)->exists();
            if ($step == 2 && !$step1Completed) {
                return redirect()->route('projects.create', ['step' => 1]);
            }

            if ($step == 3 && !$step2Completed) {
                return redirect()->route('projects.create', ['step' => 2]);
            }

            if ($step == 4 && !$step3Completed) {
                return redirect()->route('projects.create', ['step' => 3]);
            }




            if (!$project) {
                return redirect()->route('projects.index')->with('error', 'Project not found.');
            }

            if ($step == 2) {
                // Load for step 2 (measurement editing)
                $measurements = Measurement::with('area')
                    ->where('project_id', $projectId)
                    ->orderBy('id')
                    ->get();

                $grouped = [];

                foreach ($measurements as $m) {
                    $areaId = $m->area_id;

                    // Initialize area if not exists
                    if (!isset($grouped[$areaId])) {
                        $grouped[$areaId] = [
                            'name' => optional($m->area)->name ?? 'Unknown Area',
                            'rows' => []
                        ];
                    }

                    // Add row data
                    $grouped[$areaId]['rows'][] = [
                        'id' => $m->id,   // ✅ add this
                        'ref' => $m->reference,
                        'unit' => $m->unit,
                        'length' => $m->length,
                        'breadth' => $m->breadth,
                        'width' => $m->width,
                        'height' => $m->height,
                        'qty' => $m->qty,
                        'remark' => $m->remark,
                    ];
                }

                $savedMeasurements = $grouped;
            } elseif (($step == 3) || ($step == 4)) {

                if ($step == 3) {

                    $measurements = Measurement::with('area')
                        ->where('project_id', $projectId)
                        ->orderBy('id')
                        ->get();

                    $materials = Material::with(['measurement.area'])
                        ->where('project_id', $projectId)
                        ->get();

                    $materialsByArea = $materials
                        ->groupBy(function ($mat) {
                            return $mat->measurement?->area?->name ?? 'Unknown Area';
                        })
                        ->map(function ($areaMaterials) {
                            return $areaMaterials->groupBy(function ($mat) {
                                return $mat->measurement?->reference ?? 'Misc';
                            });
                        });
                }


                if ($step == 4) {

                    $quotationId = $request->quotation_id ?? null;

                    // If quotation_id not passed, get latest quotation
                    if (!$quotationId) {
                        $quotation = DB::table('quotations')
                            ->where('project_id', $projectId)
                            ->orderByDesc('version')
                            ->first();

                        $quotationId = $quotation?->id;


                        // If no quotation exists, create one
                        if (!$quotation) {
                            $quotationId = $this->createInitialQuotation($projectId);
                        } else {
                            $quotationId = $quotation->id;
                        }
                    }

                    // Fetch quotation items only once
                    $quotationItems = QuotationItem::with('product')
                        ->where('quotation_id', $quotationId)
                        ->get();

                    // Grouping
                    $materialsByArea = $quotationItems
                        ->groupBy('area_name')
                        ->map(function ($rows) {
                            return $rows->groupBy(function ($r) {
                                return $r->reference_name ?? 'Misc';
                            });
                        });
                }

            } elseif ($step == 5) {

                $payments = ReceivedPayment::with('createdBy')
                    ->where('project_id', $projectId)
                    ->latest()
                    ->paginate(10);

                $totalReceived = ReceivedPayment::where('project_id', $projectId)->sum('amount');

                $totalProjectAmount = $project->final_amount ?? 0;

                $dueAmount = max(0, $totalProjectAmount - $totalReceived);

                // ✅ AUTO SET FINAL AMOUNT TO 0 WHEN FULLY PAID
                if ($dueAmount == 0 && $totalProjectAmount > 0) {
                    $project->update([
                        'final_amount' => 0
                    ]);
                }
            }

        }

        $tailors = User::whereHas('tailor')->orderBy('name')->get();
        $sales = User::whereHas('SalesAssociate')->orderBy('name')->get();
        $interiors = Interior::orderBy('name')->get();

        // ==============================
// PRODUCT → BRAND MAP
// ==============================



        $data = [
            'step' => $step,
            'customers' => Customer::orderBy('name')->get(),
            'sales' => $sales,
            'tailors' => $tailors,
            'interiors' => $interiors,
            'stores' => Store::orderBy('storename')->get(),
            'items' => Product::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'productGroups' => ProductGroup::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'catalogues' => Catalogue::orderBy('name')->get(),
            'term' => TermCondition::where('status',1)
            ->orderBy('id','desc')
            ->first(),            'materials' => $materials,
            'step1Data' => $project ? [
                'customer_id' => $project->customer_id,
                'project_name' => $project->project_name,
                'address' => $project->address,
                'phone' => $project->phone,

                'project_deadline' => $project->project_deadline,
                'project_requirement' => $project->project_requirement,
                    'sales_associate_id' => $project->sales_associate_id, // ✅ ADD

                // ✅ missing fields
                'project_start_date' => $project->project_start_date,
                'estimated_end_date' => $project->estimated_end_date,
                'priority' => $project->priority,
            ] : session('step1', []),
            'step2Data' => session('step2', []),
            'step3Data' => session('step3', []),

            'areas' => Area::where('is_active', 1)->orderBy('name')->get(),
            'projectId' => $projectId,
            'savedMeasurements' => $savedMeasurements,
            'measurements' => $measurements,
            'project' => $project,
            'materialsByArea' => $materialsByArea,
            'payments' => $payments, // Pass payments to view
            'dueAmount' => $dueAmount, // Pass due amount to view
            'step1_completed' => $step1Completed ?? false,
            'step2_completed' => $step2Completed ?? false,
            'step3_completed' => $step3Completed ?? false,
            'step4_completed' => $step4Completed ?? false,
            'brands' => Brand::orderBy('name')->get(),


        ];

        return view('projects.create', $data);
    }
    public function start()
    {
        session()->forget([
            'project_id',
            'step1',
            'step2',
            'step3',
            'step1_completed',
            'step2_completed',
            'step3_completed',
        ]);

        return redirect()->route('projects.create', ['step' => 1]);
    }


    // ==============================
    // AJAX : FETCH CUSTOMER DETAILS
    // ==============================
    public function getCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $nextProjectNumber = Project::max('id') + 1;

        return response()->json([
            'name' => $customer->name,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'address_line1' => $customer->address_line1,
            'city' => $customer->city,  // Add this
            'state' => $customer->state, // Add this
            'pin' => $customer->pin,   // Add this
            'project_no' => str_pad($nextProjectNumber, 3, '0', STR_PAD_LEFT),
        ]);
    }

    // ==============================
    // STORE STEP 1
    // ==============================
    public function storeStep1(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'project_name' => 'required',
            'project_requirement' => 'nullable|string',
            // ✅ new fields
            'project_start_date' => 'nullable|date',
            'estimated_end_date' => 'nullable|date',
            'priority' => 'nullable|string',
            'sales_associate_id' => 'required|exists:users,id', // ✅ ADD THIS

        ]);

        $projectId = session('project_id');

        if ($projectId) {

            // ✅ UPDATE existing project
            $project = Project::findOrFail($projectId);

            $project->update([
                'customer_id' => $request->customer_id,
                'project_name' => $request->project_name,
                'address' => $request->address,
                'project_deadline' => $request->project_deadline,
                'project_requirement' => $request->project_requirement,
                'sales_associate_id' => $request->sales_associate_id, // ✅ ADD

                // ✅ new columns
                'project_start_date' => $request->project_start_date,
                'estimated_end_date' => $request->estimated_end_date,
                'priority' => $request->priority,
            ]);

        } else {

            // ✅ CREATE only first time
            $project = Project::create([
                'customer_id' => $request->customer_id,
                'project_code' => 'FMS-P-' . (Project::max('id') + 1),
                'project_name' => $request->project_name,
                'address' => $request->address,
                'project_deadline' => $request->project_deadline,
                'project_requirement' => $request->project_requirement,
                'sales_associate_id' => $request->sales_associate_id, // ✅ ADD

                'status' => 'pending',
                // ✅ new columns
                'project_start_date' => $request->project_start_date,
                'estimated_end_date' => $request->estimated_end_date,
                'priority' => $request->priority,
            ]);

            session(['project_id' => $project->id]);
        }

        session([
            'step1_completed' => true,
            'step1' => [
                'customer_id' => $request->customer_id,
                'project_name' => $request->project_name,
                'address' => $request->address,
                'project_deadline' => $request->project_deadline,
                'project_requirement' => $request->project_requirement,
                'sales_associate_id' => $request->sales_associate_id, // ✅ ADD

                // ✅ new fields in session
                'project_start_date' => $request->project_start_date,
                'estimated_end_date' => $request->estimated_end_date,
                'priority' => $request->priority,
            ]
        ]);

        return response()->json(['success' => true]);
    }
    //update for customer details


    public function updateCustomer(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        // Trim before validation
        $request->merge([
            'name' => trim($request->name),
            'phone' => trim($request->phone),
            'email' => trim($request->email),
        ]);

        $request->validate([

            'name' => 'required|string|max:255',

            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'phone')->ignore($customer->id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],

            'address_line1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin' => 'nullable|string|max:20',
        ]);

        $customer->update($request->all());

        return response()->json(['customer' => $customer]);
    }



    // ==============================
    // STORE STEP 2 (MEASUREMENTS)
    // ==============================
    public function storeStep2(Request $request)
    {
        $projectId = session('project_id');

        $request->validate([
            'measurements' => 'required|array'
        ]);

        $usedIds = [];

        foreach ($request->measurements as $areaId => $areaData) {

            if (!isset($areaData['rows']))
                continue;

            foreach ($areaData['rows'] as $row) {

                if (
                    empty($row['ref']) &&
                    empty($row['width']) &&
                    empty($row['height']) &&
                    empty($row['qty']) &&
                    empty($row['remark'])
                ) {
                    continue;
                }

                $measurement = Measurement::updateOrCreate(
                    [
                        'id' => $row['id'] ?? null   // 👈 important
                    ],
                    [
                        'project_id' => $projectId,
                        'area_id' => $areaId,
                        'reference' => $row['ref'] ?? null,
                        'unit' => $row['unit'] ?? 'CM',
                        'length' => $row['length'] !== '' ? $row['length'] : null,
                        // 'breadth'    => $row['breadth'] !== '' ? $row['breadth'] : null,
                        'width' => $row['width'] !== '' ? $row['width'] : null,
                        'height' => $row['height'] !== '' ? $row['height'] : null,
                        'qty' => $row['qty'] ?? 1,
                        'remark' => $row['remark'] ?? null,
                    ]
                );

                $usedIds[] = $measurement->id;
            }
        }

        /*
            Optional:
            only if you want removed UI rows to be deleted
        */
        Measurement::where('project_id', $projectId)
            ->whereNotIn('id', $usedIds)
            ->delete();

        session(['step2_completed' => true]);

        return response()->json(['success' => true]);
    }



    // ==============================
    // STEP 3 : MATERIAL SELECTION (Direct access with project ID)
    // ==============================
    public function step3($projectId)
    {
        $measurements = Measurement::with('area')
            ->where('project_id', $projectId)
            ->orderBy('id')
            ->get();

        if ($measurements->isEmpty()) {
            return redirect()->route('projects.create')
                ->with('error', 'No measurements found');
        }

        // ==============================
// PRODUCT → BRAND MAP (CORRECT)
// ==============================
        $productBrandMap = [];

        $productGroups = ProductGroup::select('id', 'main_product', 'addon_products')->get();

        foreach ($productGroups as $group) {

            // find brands that belong to this product group
            $brandIds = Brand::where('product_group_id', $group->id)
                ->pluck('id')
                ->toArray();

            if (empty($brandIds)) {
                continue;
            }

            // main product
            if (!empty($group->main_product)) {

                $pid = (int) $group->main_product;

                $productBrandMap[$pid] = array_values(array_unique(
                    array_merge($productBrandMap[$pid] ?? [], $brandIds)
                ));
            }

            // addon products (json)
            if (is_array($group->addon_products)) {

                foreach ($group->addon_products as $pid) {

                    $pid = (int) $pid;

                    $productBrandMap[$pid] = array_values(array_unique(
                        array_merge($productBrandMap[$pid] ?? [], $brandIds)
                    ));
                }
            }
        }



        return view('projects.step3', [
            'projectId' => $projectId,
            'measurements' => $measurements,
            'products' => Product::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'catalogues' => Catalogue::orderBy('name')->get(),
            'productBrandMap' => $productBrandMap,

        ]);
    }


    // ==============================
// STEP 4 : QUOTATION
// ==============================
    public function step4($projectId)
    {
        $this->generateQuotationItems($projectId);

        $items = QuotationItem::where('project_id', $projectId)
            ->orderBy('area_name')
            ->get()
            ->groupBy('area_name');

        $project = Project::findOrFail($projectId);

        return view('projects.step4', compact('items', 'project'));
    }



    // ==============================
    // STORE STEP 3 (MATERIALS)
    // ==============================
    public function storeStep3(Request $request)
    {
        $projectId = $request->project_id;

        $request->validate([
            'materials' => 'required|array',
            'materials.*.measurement_id' => 'required|exists:measurements,id',
        ]);

        $usedIds = [];

        foreach ($request->materials as $row) {

            if (
                empty($row['product_id']) &&
                empty($row['brand_id']) &&
                empty($row['catalogue_id']) &&
                empty($row['design_no'])
            ) {
                continue;
            }

            $material = Material::updateOrCreate(
                [
                    'id' => $row['id'] ?? null,
                ],
                [
                    'project_id' => $projectId,
                    'measurement_id' => $row['measurement_id'],
                    'product_id' => $row['product_id'] ?? null,
                    'brand_id' => $row['brand_id'] ?? null,
                    'catalogue_id' => $row['catalogue_id'] ?? null,
                    'design_no' => $row['design_no'] ?? null,
                    'mrp' => $row['mrp'] ?? 0,
                    'quantity' => $row['quantity'] ?? 1,
                    'discount' => $row['discount'] ?? 0,
                    'tax_rate' => $row['tax_rate'] ?? 0,
                ]
            );

            $usedIds[] = $material->id;
        }

        // Remove deleted materials
        Material::where('project_id', $projectId)
            ->whereNotIn('id', $usedIds)
            ->delete();

        /*
        =====================================
        🔥 IMPORTANT FIX
        =====================================
        */

        // 1️⃣ Delete old quotation items
        DB::table('quotation_items')
            ->where('project_id', $projectId)
            ->delete();

        // 2️⃣ Delete old quotations
        DB::table('quotations')
            ->where('project_id', $projectId)
            ->delete();

        session(['step3_completed' => true]);

        return redirect()->route('projects.create', [
            'step' => 4,
            'project_id' => $projectId
        ]);
    }
    // ==============================
    // ADD NEW AREA VIA AJAX
    // ==============================
    public function addArea(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:areas,name']);

        $area = Area::create(['name' => $request->name, 'is_active' => 1]);

        return response()->json(['id' => $area->id, 'name' => $area->name]);
    }

    // public function updateArea(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:areas,id',
    //         'name' => 'required|string|unique:areas,name,' . $request->id,
    //     ]);

    //     $area = Area::find($request->id);
    //     $area->update(['name' => $request->name]);

    //     return response()->json(['status' => true, 'id' => $area->id, 'name' => $area->name]);
    // }


    public function updateArea(Request $request)
{
    $request->validate([
        'id' => 'required|exists:areas,id',
        'name' => 'required|string|unique:areas,name,' . $request->id,
    ]);

    $area = Area::find($request->id);
    $area->update(['name' => $request->name]);

    return response()->json([
        'success' => true,
        'id' => $area->id,
        'name' => $area->name
    ]);
}
    // ==============================
    // SHOW PROJECT DETAILS
    // ==============================
    public function show($id)
    {
        $project = Project::with([
            'customer',
            'salesAssociate',
            'tailor',
            'interior',
            'areas.measurements.materials.product',
            'areas.measurements.materials.brand',
            'areas.measurements.materials.catalogue'
        ])->findOrFail($id);

        $users = User::orderBy('name')->get();
        $salesAssociates = SalesAssociate::orderBy('name')->get();
        $tailors = Tailor::orderBy('name')->get();
        $interiors = Interior::orderBy('name')->get(); // ✅ ADD THIS


        return view('projects.show', compact('project', 'users', 'salesAssociates', 'tailors', 'interiors'));
    }


    // ==============================
    // EDIT PROJECT
    // ==============================
    public function edit($id)
    {
        $project = Project::with(['measurements.materials'])->findOrFail($id);

        $data = [
            'project' => $project,
            'customers' => Customer::orderBy('name')->get(),
            'sales' => SalesAssociate::orderBy('name')->get(),
            'tailors' => Tailor::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'catalogues' => Catalogue::orderBy('name')->get(),
            'areas' => Area::where('is_active', 1)->orderBy('name')->get(),
        ];

        return view('projects.edit', $data);
    }

    // ==============================
    // UPDATE PROJECT
    // ==============================
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'customer_id' => 'required',
            'project_name' => 'required',
        ]);

        $project->update([
            'customer_id' => $request->customer_id,
            'project_name' => $request->project_name,
            'address' => $request->address,
            'sales_associate_id' => $request->sales_id,
            'tailor_id' => $request->tailor_id,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    // ==============================
    // DELETE PROJECT
    // ==============================
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    // public function confirm($id)
    // {
    //     $project = Project::findOrFail($id);

    //     $project->update([
    //         'status' => 'confirmed'
    //     ]);

    //     return back()->with('success', 'Project confirmed successfully');
    // }
    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,goods_ordered,assign_to_tailors,in_production,order_ready,dispatch,delivered,cancelled',
        ]);

        $data = [
            'status' => $request->status,
        ];

        // ✅ generate order id when confirmed
        if ($request->status === 'confirmed' && empty($project->order_id)) {

            $data['order_id'] =
                'ORD-' . now()->format('Y') . '-' .
                str_pad($project->id, 5, '0', STR_PAD_LEFT);
        }

        $project->update($data);

        return response()->json([
            'success' => true,
            'status' => $project->status,
        ]);
    }



    public function saveQuotationTotal(Request $request, Project $project)
    {
        $request->validate([
            'total' => 'required|numeric|min:0'
        ]);

        $project->final_amount = $request->total;
        $project->save();

        return response()->json([
            'success' => true
        ]);
    }


    public function paymentDetails(Request $request)
    {
        $projects = Project::select('id', 'project_name')
    ->when($request->status, function ($q) use ($request) {
        $q->where('status', $request->status);
    })
    ->orderBy('project_name')
    ->get();

        /*
        |--------------------------------------------------------------------------
        | Sub query : latest quotation per project
        |--------------------------------------------------------------------------
        */
        $latestQuotationSub = DB::table('quotations as q1')
            ->select('q1.id', 'q1.project_id')
            ->whereRaw('q1.version = (
            SELECT MAX(q2.version)
            FROM quotations q2
            WHERE q2.project_id = q1.project_id
        )');

        /*
        |--------------------------------------------------------------------------
        | Sub query : quotation total per quotation
        |--------------------------------------------------------------------------
        */
        $quotationTotalSub = DB::table('quotation_items')
            ->select(
                'quotation_id',
                DB::raw('SUM(total) as quotation_total')
            )
            ->groupBy('quotation_id');

        /*
        |--------------------------------------------------------------------------
        | Sub query : received total per project
        |--------------------------------------------------------------------------
        */
        $receivedTotalSub = DB::table('received_payments')
            ->select(
                'project_id',
                DB::raw('SUM(amount) as received_total')
            )
            ->groupBy('project_id');

        /*
        |--------------------------------------------------------------------------
        | Main table
        |--------------------------------------------------------------------------
        */
        $payments = Project::query()

            ->leftJoin('customers', 'customers.id', '=', 'projects.customer_id')

            ->leftJoinSub($latestQuotationSub, 'latest_quotation', function ($join) {
                $join->on('latest_quotation.project_id', '=', 'projects.id');
            })

            ->leftJoinSub($quotationTotalSub, 'qt', function ($join) {
                $join->on('qt.quotation_id', '=', 'latest_quotation.id');
            })

            ->leftJoinSub($receivedTotalSub, 'rp', function ($join) {
                $join->on('rp.project_id', '=', 'projects.id');
            })

            ->when($request->project_id, function ($q) use ($request) {
                $q->where('projects.id', $request->project_id);
            })

            ->when($request->status, function ($q) use ($request) {
                $q->where('projects.status', $request->status);
            })

            ->select(
                'projects.id as project_id',
                'projects.project_name',
                'customers.name as customer_name',
                'projects.status',

                DB::raw('COALESCE(qt.quotation_total,0) as total_amount'),
                DB::raw('COALESCE(rp.received_total,0) as received_amount'),
                DB::raw('(COALESCE(qt.quotation_total,0) - COALESCE(rp.received_total,0)) as due_amount'),

                'projects.created_at as date'
            )
            ->orderByDesc('projects.id')
            ->paginate(25)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary cards
        |--------------------------------------------------------------------------
        */
        $summary = Project::query()

            ->leftJoinSub($latestQuotationSub, 'latest_quotation', function ($join) {
                $join->on('latest_quotation.project_id', '=', 'projects.id');
            })

            ->leftJoinSub($quotationTotalSub, 'qt', function ($join) {
                $join->on('qt.quotation_id', '=', 'latest_quotation.id');
            })

            ->leftJoinSub($receivedTotalSub, 'rp', function ($join) {
                $join->on('rp.project_id', '=', 'projects.id');
            })

            ->when($request->project_id, function ($q) use ($request) {
                $q->where('projects.id', $request->project_id);
            })

            ->when($request->status, function ($q) use ($request) {
                $q->where('projects.status', $request->status);
            })

            ->selectRaw('
            COUNT(DISTINCT projects.id) as total_projects,
            COALESCE(SUM(qt.quotation_total),0) as total_amount,
            COALESCE(SUM(rp.received_total),0) as received_amount,
            COALESCE(SUM(qt.quotation_total),0) - COALESCE(SUM(rp.received_total),0) as pending_amount
        ')
            ->first();


        $totalProjects = $summary->total_projects;
        $totalAmount = $summary->total_amount;
        $receivedAmount = $summary->received_amount;
        $pendingAmount = $summary->pending_amount;


        return view('projects.paymentdetails', compact(
            'projects',
            'payments',
            'totalProjects',
            'totalAmount',
            'receivedAmount',
            'pendingAmount'
        ));
    }




    //pdf generate for payment


    public function receipt(Project $project, ReceivedPayment $payment)
    {
        $project->load('customer');

        $pdf = Pdf::loadView('pdf.payment-receipt', [
            'project' => $project,
            'payment' => $payment
        ])->setPaper('A4');

        return $pdf->download(
            'Payment_Receipt_' . $payment->id . '.pdf'
        );
    }

    //pdf generate for  quotation


    public function quotationPdf(Project $project, Request $request)
    {
        $quotationId = $request->quotation_id;

        if (!$quotationId) {
            $quotation = Quotation::where('project_id', $project->id)
                ->orderByDesc('version')
                ->first();
            $quotationId = $quotation?->id;
        }

        $quotation = Quotation::find($quotationId);
        $items = QuotationItem::with('product')
            ->where('quotation_id', $quotationId)
            ->get();

        $materialsByArea = $items
            ->groupBy('area_name')
            ->map(function ($rows) {
                return $rows->groupBy(fn($r) => $r->reference_name ?? 'Misc');
            });

                // Fetch latest active term
    $term = TermCondition::where('status',1)
            ->latest('id')
            ->first();
        $pdf = Pdf::loadView('pdf.quotation', [
            'project' => $project,
            'quotation' => $quotation,
            'materialsByArea' => $materialsByArea,
             'term' => $term
        ])->setPaper('A4', 'landscape');

        return $pdf->download(
            'Quotation_' . $project->id . '_V' . $quotation->version . '.pdf'
        );
    }




    public function viewPdf(Project $project, Request $request)
    {
        $quotationId = $request->quotation_id;

        if (!$quotationId) {
            $quotation = Quotation::where('project_id', $project->id)
                ->orderByDesc('version')
                ->first();

            $quotationId = $quotation?->id;
        }

        $items = QuotationItem::with('product')
            ->where('quotation_id', $quotationId)
            ->get();

        $materialsByArea = $items
            ->groupBy('area_name')
            ->map(function ($rows) {
                return $rows->groupBy(fn($r) => $r->reference_name ?? 'Misc');
            });

        $quotation = Quotation::findOrFail($quotationId);

        $pdf = Pdf::loadView('pdf.quotation', [
            'project' => $project,
            'quotation' => $quotation,
            'materialsByArea' => $materialsByArea
        ])->setPaper('A4', 'landscape');

        // ✅ only difference
        return $pdf->stream(
            'Quotation_' . $project->id . '_V' . $quotation->version . '.pdf'
        );
    }

    private function createInitialQuotation($projectId)
    {
        $last = DB::table('quotations')
            ->where('project_id', $projectId)
            ->orderByDesc('version')
            ->first();

        $version = $last ? $last->version + 1 : 1;

        $quotationId = DB::table('quotations')->insertGetId([
            'project_id' => $projectId,
            'version' => $version,
            'grand_total' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $materials = Material::with(['measurement.area', 'product'])
            ->where('project_id', $projectId)
            ->get();

        $grandTotal = 0;

        foreach ($materials as $mat) {

            $measurement = $mat->measurement;

            $areaName = $measurement?->area?->name ?? 'Unknown Area';

            $qty = $measurement?->qty ?? 1;
            $rate = $mat->mrp ?? 0;
            $discount = $mat->discount ?? 0;
            $tax = $mat->tax_rate ?? 0;

            $afterDiscount = $rate - ($rate * $discount / 100);
            $saleRate = $afterDiscount + ($afterDiscount * $tax / 100);
            $total = $saleRate * $qty;

            $grandTotal += $total;

            DB::table('quotation_items')->insert([
                'quotation_id' => $quotationId,
                'project_id' => $projectId,

                'area_name' => $areaName,
                'reference_name' => $measurement?->reference,

                'product_id' => $mat->product_id,

                'length' => $measurement?->length,
                'breadth' => $measurement?->breadth,
                'width' => $measurement?->width,
                'height' => $measurement?->height,

                'unit' => $measurement?->unit ?? 'Nos',
                'qty' => $qty,

                'rate' => $rate,
                'discount' => $discount,
                'tax_rate' => $tax,
                'sale_rate' => $saleRate,
                'total' => $total,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('quotations')
            ->where('id', $quotationId)
            ->update(['grand_total' => $grandTotal]);

        return $quotationId;
    }
    public function reviseQuotation(Project $project)
    {
        $newQuotationId = $this->createInitialQuotation($project->id);

        return redirect()->route('projects.create', [
            'step' => 4,
            'project_id' => $project->id,
            'quotation_id' => $newQuotationId
        ]);
    }


    public function createNewQuotationVersion(Project $project)
    {
        $last = DB::table('quotations')
            ->where('project_id', $project->id)
            ->orderByDesc('version')
            ->first();

        $newQuotationId = $this->createInitialQuotation($project->id);

        return redirect()->route(
            'projects.quotation.pdf',
            [$project->id, 'quotation' => $newQuotationId]
        );
    }


public function updateItem(Request $request)
{
    DB::beginTransaction();

    try {

        $item = QuotationItem::findOrFail($request->id);
        $quotation = Quotation::findOrFail($item->quotation_id);

        $qty = (float) $request->qty;
        $rate = (float) $request->rate;
        $discount = (float) $request->discount;
        $taxRate = (float) $request->tax; // from JS
        $unit = $request->unit;

        // calculations
        $amount = $qty * $rate;

        $discountAmount = ($amount * $discount) / 100;

        $taxableAmount = $amount - $discountAmount;

        $taxAmount = ($taxableAmount * $taxRate) / 100;

        $total = $taxableAmount + $taxAmount;

        // update quotation item
        $item->update([
            'qty' => $qty,
            'rate' => $rate,
            'discount' => $discount,
            'tax_rate' => $taxRate, // correct column
            'unit' => $unit,
            'sale_rate' => $rate,
            'total' => $total
        ]);

        // recalc totals
        $items = QuotationItem::where('quotation_id', $quotation->id)->get();

        $subTotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $grandTotal = 0;

        foreach ($items as $i) {

            $amount = $i->qty * $i->rate;

            $discountAmount = ($amount * $i->discount) / 100;

            $taxableAmount = $amount - $discountAmount;

            $taxAmount = ($taxableAmount * $i->tax_rate) / 100;

            $rowTotal = $taxableAmount + $taxAmount;

            $subTotal += $amount;
            $totalDiscount += $discountAmount;
            $totalTax += $taxAmount;
            $grandTotal += $rowTotal;
        }

        // update quotation table
        $quotation->update([
            'sub_total' => $subTotal,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'sub_total' => $subTotal,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

    // Helper method for unit conversion
    private function getUnitFactor($unit)
    {
        $unitCostMap = [
            'CM' => 1,
            'INCH' => 2.54,
            'FT' => 30.48,
            'M' => 100
        ];

        return $unitCostMap[$unit] ?? 1;
    }

    // In ProjectController.php
    public function saveQuotationTerms(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'terms' => 'nullable|string'
        ]);

        $quotation = Quotation::findOrFail($request->quotation_id);
        $quotation->terms_and_conditions = $request->terms;
        $quotation->save();

        return response()->json(['success' => true]);
    }

    public function storePayment(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                function ($attr, $value, $fail) use ($project) {

                    $totalReceived = ReceivedPayment::where('project_id', $project->id)->sum('amount');
                    $dueAmount = max(0, ($project->final_amount ?? 0) - $totalReceived);

                    if ($value > $dueAmount) {
                        $fail('Payment amount cannot be greater than due amount.');
                    }
                }
            ],
            'payment_mode' => 'required|string',
            'transaction_number' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        ReceivedPayment::create([
            'project_id' => $project->id,
            'order_id' => $project->order_id, // ✅ auto save
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'transaction_number' => $request->transaction_number,
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Payment added successfully');
    }


    //export for order

    public function export($type, Request $request, ProjectQueryService $service)
    {
        $search = $request->search;

        // ================= EXCEL / CSV =================
        if ($type === 'excel' || $type === 'csv') {

            $fileName = 'projects_' . now()->format('Y_m_d_H_i_s') . '.' .
                ($type === 'excel' ? 'xlsx' : 'csv');

            return Excel::download(
                new ProjectsExport($search),
                $fileName
            );
        }

        // ================= PDF =================
        if ($type === 'pdf') {

            $projects = $service
                ->list($search)
                ->get();

            $pdf = Pdf::loadView('pdf.export_projects', compact('projects'));

            return $pdf->download(
                'projects_' . now()->format('Y_m_d_H_i_s') . '.pdf'
            );
        }

        abort(404);
    }


    public function approve(Project $project)
    {
        $this->authorize('approve project');

        if ($project->status === 'confirmed') {
            return back()->with('info', 'Project already confirmed.');
        }

        $project->update([
            'status' => 'confirmed',
            'order_id' => $project->order_id ?? (
                'ORD-' . now()->format('Y') . '-' .
                str_pad($project->id, 5, '0', STR_PAD_LEFT)
            ),
        ]);

        return back()->with('success', 'Project confirmed successfully.');
    }




    public function measurementPdf(Project $project)
    {
        $items = QuotationItem::with('product')
            ->where('project_id', $project->id)
            ->orderBy('area_name')
            ->get();

        $grouped = $items->groupBy('area_name');

        $pdf = Pdf::loadView('pdf.measurements', [
            'project' => $project,
            'grouped' => $grouped
        ])->setPaper('A4', 'landscape');

        return $pdf->download(
            'Measurements_Project_' . $project->id . '.pdf'
        );
    }


    public function paymentsReport()
    {
        $totalProjects = Project::count();

        return view('payments.report', compact('totalProjects'));
    }

   public function orders()
{
    $query = Project::whereNotNull('order_id');

    // 🔥 If logged user is Sales Associate → show only assigned projects
    if (auth()->check() && auth()->user()->hasRole('sales_associates')) {
        $query->where('sales_associate_id', auth()->id());
    }

    $projects = $query
        ->orderByDesc('id')
        ->paginate(10);

    return view('projects.order', compact('projects'));
}

   public function downloadInvoice(Invoice $invoice)
{
    $project = $invoice->project()->with('customer')->first();

    // get latest quotation
    $quotation = DB::table('quotations')
        ->where('project_id', $project->id)
        ->orderByDesc('version')
        ->first();

    $items = QuotationItem::where('quotation_id', $quotation->id)->get();

    $materialsByArea = $items
        ->groupBy('area_name')
        ->map(function ($rows) {
            return $rows->groupBy(fn($r) => $r->reference_name ?? 'Misc');
        });

    $pdf = Pdf::loadView('invoices.invoice', [
        'invoice' => $invoice,
        'project' => $project,
        'materialsByArea' => $materialsByArea,
        'quotation' => $quotation
    ])->setPaper('A4', 'portrait');

    return $pdf->download(
        'Invoice_' . $invoice->invoice_no . '.pdf'
    );
}

public function printInvoice(Invoice $invoice)
{
    $project = $invoice->project()->with('customer')->first();

    $quotation = DB::table('quotations')
        ->where('project_id', $project->id)
        ->orderByDesc('version')
        ->first();

    $items = QuotationItem::where('quotation_id', $quotation->id)->get();

    $materialsByArea = $items
        ->groupBy('area_name')
        ->map(function ($rows) {
            return $rows->groupBy(fn($r) => $r->reference_name ?? 'Misc');
        });

    return view('invoices.invoice', [
        'invoice' => $invoice,
        'project' => $project,
        'materialsByArea' => $materialsByArea,
        'quotation' => $quotation
    ]);
}

public function quotationPreview(Project $project, Request $request)
{
    $quotationId = $request->quotation_id;

    if (!$quotationId) {
        $quotation = Quotation::where('project_id', $project->id)
            ->orderByDesc('version')
            ->first();
        $quotationId = $quotation?->id;
    }

    $quotation = Quotation::findOrFail($quotationId);

    $items = QuotationItem::with('product')
        ->where('quotation_id', $quotationId)
        ->get();

    $materialsByArea = $items
        ->groupBy('area_name')
        ->map(function ($rows) {
            return $rows->groupBy(fn ($r) => $r->reference_name ?? 'Misc');
        });

    // ✅ Fetch latest active Terms & Conditions
    $term = TermCondition::where('status', 1)
        ->latest('id')
        ->first();

    return view('pdf.quotation', [
        'project' => $project,
        'quotation' => $quotation,
        'materialsByArea' => $materialsByArea,
        'term' => $term   // ✅ Pass to blade
    ]);
}

    public function generateInvoice(Project $project)
    {
        // Check if invoice already exists for project
        if (Invoice::where('project_id', $project->id)->exists()) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Invoice already generated for this project.');
        }

        $items = QuotationItem::where('project_id', $project->id)->get();
        $grandTotal = $items->sum('total');

        // SAFE invoice number generation
        $lastInvoice = Invoice::latest('id')->first();
        $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;

        $invoiceNo = 'INV-' . now()->format('Ymd') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Invoice::create([
            'project_id' => $project->id,
            'order_id' => $project->order_id,
            'invoice_no' => $invoiceNo,
            'grand_total' => $grandTotal,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Invoice generated successfully.');
    }


    public function receivedPayments(Project $project)
    {
        $projectId = $project->id;

        // ✅ latest quotation (by version)
        $latestQuotation = \DB::table('quotations')
            ->where('project_id', $projectId)
            ->orderByDesc('version')
            ->first();

        // ✅ TOTAL AMOUNT from quotations.grand_total
        $totalAmount = $latestQuotation?->grand_total ?? 0;

        // ✅ TOTAL PAID
        $totalPaid = \DB::table('received_payments')
            ->where('project_id', $projectId)
            ->sum('amount');

        // ✅ DUE
        $dueAmount = $totalAmount - $totalPaid;

        // ✅ payments list
        $payments = \DB::table('received_payments')
            ->where('project_id', $projectId)
            ->latest('id')
            ->paginate(10);

        return view('projects.received-payments', compact(
            'project',
            'payments',
            'totalAmount',
            'totalPaid',
            'dueAmount'
        ));
    }

    // public function deleteArea(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:areas,id'
    //     ]);

    //     // Important: check if area is already used in measurements
    //     $used = Measurement::where('area_id', $request->id)->exists();

    //     if ($used) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Area already used in measurements'
    //         ], 422);
    //     }

    //     Area::where('id', $request->id)->delete();

    //     return response()->json([
    //         'status' => true
    //     ]);
    // }

    public function deleteMeasurement(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        DB::table('measurements')
            ->where('id', $request->id)
            ->delete();

        return response()->json(['success' => true]);
    }


    public function assignInterior(Request $request, Project $project)
    {
        $request->validate([
            'interior_id' => 'required|exists:interiors,id'
        ]);

        $project->update([
            'interior_id' => $request->interior_id
        ]);

        return response()->json(['success' => true]);
    }


    public function deleteRow(Request $request)
{
    $rowId = $request->row_id;
    // Delete the measurement row from database
    Measurement::where('id', $rowId)->delete();

    return response()->json(['success' => true]);
}

public function deleteArea(Request $request)
{
    $areaId = $request->area_id;
    $projectId = $request->project_id;

    // Delete all measurements for this area and project
    Measurement::where('project_id', $projectId)
               ->where('area_id', $areaId)
               ->delete();

    return response()->json(['success' => true]);
}
}
