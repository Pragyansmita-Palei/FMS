<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
       'customer_code',
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
     public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
}
