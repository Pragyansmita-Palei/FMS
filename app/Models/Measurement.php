<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $table = 'measurements';

    protected $fillable = [
        'area_id',
        'project_id',
        'reference',
        'unit',
        'length',
        'breadth',
        'width',
        'height',
        'qty',
        'remark',
    ];

    protected $casts = [
        'width'  => 'decimal:2',
        'height' => 'decimal:2',
        'qty'    => 'integer',
    ];

    public function area()
    {
        return $this->belongsTo(\App\Models\Area::class, 'area_id');
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
    }
public function materials() {
    return $this->hasMany(ProjectMaterial::class, 'measurement_id');
}


public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}
