<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'project_id',
        'version',
        'grand_total',
        'terms_and_conditions',
        'sub_total',
        'total_tax',
        'total_discount'
    ];
}
