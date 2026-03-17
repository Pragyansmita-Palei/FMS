<?php

// app/Models/Labour.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labour extends Model
{
    use HasFactory;

    protected $fillable = [
        'labour_name',
        'phone_number',
        'email',
        'address',
       'rate_type',
       'price'
    ];
}
