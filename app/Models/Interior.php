<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interior extends Model
{
    protected $fillable = [
        'firm_name',
        'email',
        'phone',
        'address',
    ];
}