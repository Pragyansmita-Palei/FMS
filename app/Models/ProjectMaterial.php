<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterial extends Model
{
    protected $fillable = [
        'project_id',
        'store',
        'item_name',
        'product_group',
        'company',
        'catalogue',
        'design_no',
        'mrp',
        'discount',
        'total_price',
         'reference',
         'measurement',
         'width',
         'height',
         'qty'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function measurement()
{
    return $this->hasOne(
        \App\Models\ProjectMeasurement::class,
        'project_id',
        'project_id'
    );
}

}
