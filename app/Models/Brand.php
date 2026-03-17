<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
 protected $fillable=[
'name','description','status',
 ];
   public function catalogues()
    {
        return $this->hasMany(Catalogue::class);
    }
      public function materials()
    {
        return $this->hasMany(Material::class);
    }
}

