<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $table = 'quotation_items';

    protected $fillable = [

        'quotation_id',
        'project_id',

        'area_name',
        'reference_name',

        // ✅ real column
        'product_id',

        // ✅ missing before
        'length',
        'breadth',
        'width',
        'height',

        'unit',
        'qty',

        'rate',
        'discount',
        'tax_rate',
        'sale_rate',
        'total',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

