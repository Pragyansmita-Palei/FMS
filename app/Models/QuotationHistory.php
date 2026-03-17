<?php
// app/Models/QuotationHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'quotation_item_id',
        'version',
        'area_name',
        'reference_name',
        'product_id',
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
        'grand_total',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'breadth' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'qty' => 'decimal:2',
        'rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'sale_rate' => 'decimal:2',
        'total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function quotationItem()
    {
        return $this->belongsTo(QuotationItem::class);
    }
}