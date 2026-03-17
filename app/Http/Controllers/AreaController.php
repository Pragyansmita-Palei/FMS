<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $area = Area::create([
            'name' => $request->name,
            'is_active' => 1, // default active
        ]);

        return response()->json($area);
    }
    
}
