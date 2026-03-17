<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'measurement_id',
        'product_id',
        'brand_id',
        'catalogue_id',
        'design_no',
        'mrp',
        'quantity',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

 public function product()
{
    return $this->belongsTo(Product::class);
}

public function brand()
{
    return $this->belongsTo(Brand::class);
}

public function catalogue()
{
    return $this->belongsTo(Catalogue::class);
}

public function measurement()
{
    return $this->belongsTo(Measurement::class);
}


}
