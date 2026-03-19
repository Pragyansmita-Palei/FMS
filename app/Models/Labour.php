<?php

// app/Models/Labour.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labour extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
       'rate_type',
       'price'
    ];

public function user()
{
    return $this->belongsTo(User::class);
}
}
