<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingUnit extends Model
{
    protected $fillable = ['group_type_id','unit_name'];

    public function groupType(){
        return $this->belongsTo(GroupType::class);
    }
}

