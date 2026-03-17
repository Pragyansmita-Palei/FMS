<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tailor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        // 🔥 ADD THIS
        'tailor_id',
        'name',
        'phone',
        'alternate_phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pin',
        'landmark',
    ];
 public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
