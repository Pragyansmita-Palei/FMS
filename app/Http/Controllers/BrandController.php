<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\ProductGroup;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::latest()->paginate(10); // 10 items per page
        return view('brands.index', compact('brands'));
    }

    public function create() {
            $productGroups = ProductGroup::where('status', 1)->get();

        return view('brands.create',compact('productGroups'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:brands,name',
        'description' => 'nullable|string',
    ]);

    Brand::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status ?? 0,
    ]);

    return redirect()
        ->route('brands.index')
        ->with('success','Brand Created Successfully');
}

    public function edit(Brand $brand) {
            $productGroups = ProductGroup::where('status', 1)->get();

        return view('brands.edit', compact('brand','productGroups'));
    }

  public function update(Request $request, Brand $brand)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
        'description' => 'nullable|string',
    ]);

    $brand->update([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status ? 1 : 0,
    ]);

    return redirect()
        ->route('brands.index')
        ->with('success', 'Brand Updated Successfully');
}

    public function destroy(Brand $brand) {
        $brand->delete();
        return back()->with('success', 'Brand deleted successfully');
    }
}
