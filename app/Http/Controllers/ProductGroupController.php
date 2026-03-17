<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Exports\ProductGroupsExport;
use App\Imports\ProductGroupsImport;
use App\Exports\ProductGroupsSampleExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductGroupController extends Controller
{
  public function index()
{
    $groups = ProductGroup::with('mainProduct')->paginate(10);
    return view('product_groups.index', compact('groups'));
}


    public function create()
    {

        $items = Product::where('publish', 1)->get();
        return view('product_groups.create', compact('items'));
    }

public function store(Request $r)
{
    $r->validate([
        'name' => 'required|string|max:255|unique:product_groups,name',
        'main_product' => 'required|exists:products,id',
        'addon_products' => 'nullable|array',
        'addon_products.*' => 'exists:products,id',
    ]);

    ProductGroup::create([
        'name' => $r->name,
        'main_product' => (int) $r->main_product,
        'addon_products' => $r->addon_products ?? [],
        'color' => $r->color,
        'status' => $r->has('status') ? 1 : 0,
    ]);

    return redirect()->route('product-groups.index');
}


    public function edit(ProductGroup $product_group)
    {
        $items = Product::where('publish', 1)->get();
        return view('product_groups.edit', compact('product_group', 'items'));
    }

   public function update(Request $r, ProductGroup $product_group)
{
    $r->validate([
        'name' => 'required|string|max:255|unique:product_groups,name,' . $product_group->id,
        'main_product' => 'required|string',
        'addon_products' => 'nullable|array',
    ]);

   $product_group->update([
    'name' => $r->name,
    'main_product' => $r->main_product,
    'addon_products' => $r->addon_products,
    'color' => $r->color,
    'status' => $r->has('status') ? 1 : 0,
]);


    return redirect()->route('product-groups.index');
}


    public function destroy(ProductGroup $product_group)
    {
        $product_group->delete();
        return back();
    }

  public function export()
{
    return Excel::download(new ProductGroupsExport, 'product-groups.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new ProductGroupsImport, $request->file('file'));

    return back()->with('success', 'Product Groups imported successfully');
}

public function downloadSample()
{
    return Excel::download(
        new ProductGroupsSampleExport,
        'product-groups-sample.xlsx'
    );
}


}
