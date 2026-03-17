<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
 protected $fillable=[
  'brand_id','name','description','status','image'
 ];
  public function brand()
 {
     return $this->belongsTo(Brand::class);
 }
   public function materials()
    {
        return $this->hasMany(Material::class);
    }
}

