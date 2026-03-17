<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
 protected $fillable=[
  'store_id', 'branch_id', 'name','description', 'group_type_id', 'brand_id',
 'selling_unit_id','mrp','tax_rate','publish','item_code','discount','total_price','quantity','design_number'
 ];

 public function store(){
  return $this->belongsTo(Store::class);
 }
   public function materials()
    {
        return $this->hasMany(Material::class);
    }


    public function groupType()
    {
        return $this->belongsTo(GroupType::class);
    }

    public function sellingUnit()
    {
        return $this->belongsTo(SellingUnit::class);
    }
    public function brand()
{
    return $this->belongsTo(Brand::class);
}

// Add branch relationship
public function branch()
{
    return $this->belongsTo(Branch::class);
}

}
