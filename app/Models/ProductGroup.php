<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    protected $fillable = [
        'name',
        'main_product',
        'addon_products',
        'color',
        'status',
    ];

       protected $casts = [
        'addon_products' => 'array',
    ];

    // 🔗 Main product relation
    public function mainProduct()
    {
        return $this->belongsTo(Product::class, 'main_product');
    }

    // 🔗 Addon products relation
    public function addonProducts()
    {
        return Product::whereIn('id', $this->addon_products ?? [])->get();
    }
}
