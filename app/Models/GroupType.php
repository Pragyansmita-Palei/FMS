<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupType extends Model
{
    protected $fillable = ['name'];

    public function sellingUnits(){
        return $this->hasMany(SellingUnit::class);
    }
}

