<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\File;

class CatalogueController extends Controller
{
   public function index(Request $request)
{
    // Optional: implement search
    $query = Catalogue::latest();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    // Pagination: 10 items per page
    $catalogues = $query->paginate(10)->withQueryString();

    // Fetch all brands for Add/Edit modals
    $brands = Brand::all();

    return view('catalogues.index', compact('catalogues', 'brands'));
}

    public function create()
    {
        $brands = Brand::all();
        return view('catalogues.create',compact('brands'));
    }


public function store(Request $r)
{
    $path = config('catalogue.image_path');
    $imageName = null;

    if($r->hasFile('image')){
        $imageName = time().'.'.$r->image->extension();

        if(!File::exists(public_path($path))){
            File::makeDirectory(public_path($path),0755,true);
        }

        $r->image->move(public_path($path),$imageName);
    }

    Catalogue::create([
        'brand_id' => $r->brand_id,
        'name' => $r->name,
        'description' => $r->description,
        'status' => $r->status ?? 0,
        'image' => $imageName,
    ]);

    return redirect()->route('catalogues.index');
}




    public function edit(Catalogue $catalogue)
    {
        $brands = Brand::all();
        return view('catalogues.edit', compact('catalogue','brands'));
    }
public function update(Request $r, Catalogue $catalogue)
{
    $path = config('catalogue.image_path');
    $imageName = $catalogue->image;

    if($r->hasFile('image')){

        if($catalogue->image &&
           file_exists(public_path($path.'/'.$catalogue->image))){
            unlink(public_path($path.'/'.$catalogue->image));
        }

        $imageName = time().'.'.$r->image->extension();
        $r->image->move(public_path($path),$imageName);
    }

    $catalogue->update([
        'name' => $r->name,
        'description' => $r->description,
        'status' => $r->status ?? 0,
        'image' => $imageName,
    ]);

    return redirect()->route('catalogues.index');
}

    public function destroy(Catalogue $catalogue)
    {
        $catalogue->delete();
        return back();
    }
}
