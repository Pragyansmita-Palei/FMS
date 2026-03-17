<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\GroupType;
use App\Models\SellingUnit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\ProductsImport;
use App\Models\Brand;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $perPage = $request->per_page ?? 10;
        $storeId = $request->store_id;

        $products = Product::with('store')
            ->latest()
            ->when($storeId, function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhereHas('store', function ($s) use ($search) {
                            $s->where('storename', 'like', "%{$search}%");
                        })
                        ->orWhere('group_type', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->appends($request->query());

        $stores = Store::orderBy('storename')->get();
        $groupTypes = GroupType::where('status', 1)->get();   // ✅ ADD
        $brands = Brand::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'search',
            'perPage',
            'stores',
            'storeId',
            'groupTypes',   // ✅ ADD
            'brands'        // ✅ ADD
        ));
    }



    public function create()
    {
        $stores = Store::all();
        $groupTypes = GroupType::where('status', 1)->get();
        $brands = Brand::orderBy('name')->get();


        return view('products.create', compact('stores', 'groupTypes', 'brands'));
    }

    // AJAX
    // AJAX
    public function getUnitsById($groupId)
    {
        return SellingUnit::where('group_type_id', $groupId)
            ->orderBy('unit_name')
            ->get();
    }



    // STORE PRODUCT


    public function store(Request $r)
    {
        $r->validate([
            'store_id' => 'required',
            'branch_id' => 'required',
            'group_type' => 'required',
            'selling_unit' => 'required',
            'name' => 'required',
            'quantity' => 'nullable|numeric|min:1',
            'mrp' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0',
            'design_number' => 'required|string|max:255|unique:products,design_number',

        ]);

        /* ----------------------------
           GROUP TYPE
        ----------------------------*/
        if ($r->group_type === '__other__') {

            $group = GroupType::create([
                'name' => $r->new_group_type,
                'status' => 1
            ]);

            $groupTypeId = $group->id;

        } else {

            $groupTypeId = $r->group_type;
        }

        /* ----------------------------
           SELLING UNIT
        ----------------------------*/
        if ($r->selling_unit === '__other__') {

            $unit = SellingUnit::create([
                'unit_name' => $r->new_selling_unit,
                'group_type_id' => $groupTypeId
            ]);

            $sellingUnitId = $unit->id;

        } else {

            $unit = SellingUnit::firstOrCreate([
                'unit_name' => $r->selling_unit,
                'group_type_id' => $groupTypeId
            ]);

            $sellingUnitId = $unit->id;
        }

        /* ----------------------------
           DUPLICATE CHECK
        ----------------------------*/
        $product = Product::where([
            'store_id' => $r->store_id,
            'group_type_id' => $groupTypeId,
            'selling_unit_id' => $sellingUnitId,
            'name' => $r->name
        ])->first();
        /* ----------------------------
           BRAND (select OR type)
        ----------------------------*/
        $brand = Brand::firstOrCreate([
            'name' => trim($r->brand_name)
        ]);

        $brandId = $brand->id;


        $mrp = $r->mrp;
        $qty = $r->quantity ?? 0;
        $taxRate = $r->tax_rate ?? 0;
        $discount = $r->discount ?? 0;

        $base = $mrp * $qty;

        $taxAmount = $base * $taxRate / 100;
        $discountAmount = $base * $discount / 100;

        $total = $base + $taxAmount - $discountAmount;


        if ($product) {
            $product->increment('quantity', $r->quantity);
            return redirect()->route('products.index')
                ->with('success', 'Quantity Updated Successfully');
        }

        $count = Product::count() + 1;
        $itemCode = 'FMS-I-' . $count;

        Product::create([
            'item_code' => $itemCode,
            'store_id' => $r->store_id,
            'branch_id' => $r->branch_id,
            'group_type_id' => $groupTypeId,
            'brand_id' => $brandId,
            'selling_unit_id' => $sellingUnitId,
            'name' => $r->name,
            'description' => $r->description,
            'mrp' => $mrp,
            'discount' => $discount,
            'total_price' => round($total, 2),
            'tax_rate' => $r->tax_rate,
            'quantity' => $r->quantity,
            'design_number' => $r->design_number,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product Added Successfully');
    }




    // EDIT
    public function edit(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'item_code' => $product->item_code,
            'store_id' => $product->store_id,
            'branch_id' => $product->branch_id,
            'brand_id' => $product->brand_id,
            'name' => $product->name,
            'quantity' => $product->quantity,
            'design_number' => $product->design_number,
            'description' => $product->description,
            'group_type_id' => $product->group_type_id,
            'selling_unit' => optional($product->sellingUnit)->unit_name,
            'mrp' => $product->mrp,
            'tax_rate' => $product->tax_rate,
            'discount' => $product->discount,
        ]);
    }

    // UPDATE
    public function update(Request $r, Product $product)
    {
        $r->validate([
            'store_id' => 'required',
            'branch_id' => 'required',
            'group_type' => 'required',
            'selling_unit' => 'required',
            'brand_id' => 'required',
            'name' => 'required',
            'mrp' => 'required|numeric',
            'quantity' => 'required|numeric|min:0',
            'design_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($product->id)
            ],
        ]);

        /* ----------------------------
           GROUP TYPE
        ----------------------------*/
        if ($r->group_type === '__other__') {

            $group = GroupType::create([
                'name' => $r->new_group_type,
                'status' => 1
            ]);

            $groupTypeId = $group->id;

        } else {
            $groupTypeId = $r->group_type;
        }

        /* ----------------------------
           SELLING UNIT  (FIXED)
        ----------------------------*/
        if ($r->selling_unit === '__other__') {

            $unit = SellingUnit::create([
                'unit_name' => $r->new_selling_unit,
                'group_type_id' => $groupTypeId
            ]);

            $sellingUnitId = $unit->id;

        } else {

            $unit = SellingUnit::firstOrCreate([
                'unit_name' => $r->selling_unit,
                'group_type_id' => $groupTypeId
            ]);

            $sellingUnitId = $unit->id;
        }

        /* ----------------------------
           BRAND (select or type)
        ----------------------------*/
        if (is_numeric($r->brand_id)) {

            $brandId = $r->brand_id;

        } else {

            $brand = Brand::firstOrCreate([
                'name' => trim($r->brand_id)
            ]);

            $brandId = $brand->id;
        }

        /* ----------------------------
           CALCULATION
        ----------------------------*/
        $mrp = $r->mrp;
        $qty = $r->quantity ?? 0;
        $taxRate = $r->tax_rate ?? 0;
        $discount = $r->discount ?? 0;

        $base = $mrp * $qty;

        $taxAmount = $base * $taxRate / 100;
        $discountAmount = $base * $discount / 100;

        $total = $base + $taxAmount - $discountAmount;

        /* ----------------------------
           UPDATE
        ----------------------------*/
        $product->update([
            'store_id' => $r->store_id,
            'branch_id' => $r->branch_id,
            'group_type_id' => $groupTypeId,
            'selling_unit_id' => $sellingUnitId,
            'brand_id' => $brandId,
            'name' => $r->name,
            'description' => $r->description,
            'mrp' => $mrp,
            'discount' => $discount,
            'total_price' => round($total, 2),
            'tax_rate' => $r->tax_rate,
            'quantity' => $r->quantity,
            'design_number' => $r->design_number,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Updated Successfully');
    }

    // DELETE
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product Deleted');
    }

    //view
    public function view($id)
    {
        $product = Product::with(['store', 'branch', 'groupType', 'sellingUnit', 'brand'])
            ->findOrFail($id);

        return response()->json([
            'item_code' => $product->item_code,
            'store_name' => $product->store->storename ?? '',
            'branch_name' => $product->branch->branch_name ?? '',
            'brand_name' => $product->brand->name ?? '',
            'name' => $product->name,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'group_type_name' => $product->groupType->name ?? '',
            'selling_unit_name' => $product->sellingUnit->unit_name ?? '',
            'mrp' => $product->mrp,
            'tax_rate' => $product->tax_rate,
            'discount' => $product->discount,
            'total_price' => $product->total_price,
        ]);
    }


    // Add this method inside ProductController
    public function exportPdf()
    {
        // Fetch all products with their related store
        $products = Product::with('store')->latest()->get();

        // Load the view and pass products
        $pdf = Pdf::loadView('pdf.product_pdf', compact('products'));

        // Download the PDF
        return $pdf->download('products.pdf');
    }

    // Show bulk import form
    public function importForm()
    {
        return view('products.import'); // Create a Blade with file input
    }

    // Handle Excel import

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('products.index')
            ->with('success', 'Products imported successfully!');
    }

    public function getBranches(Store $store)
    {
        return $store->branches()->select('id', 'branch_name')->get();
    }

}
