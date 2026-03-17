<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesAssociate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sales_id',
        'phone',
        'alternate_phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pin',
        'landmark',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {

            // do not override if already set
            if ($model->sales_id) {
                return;
            }

            $last = DB::table('sales_associates')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $next = 1;

            if ($last && $last->sales_id) {
                $next = (int) str_replace('FMS-SA-', '', $last->sales_id) + 1;
            }

            $model->sales_id = 'FMS-SA-' . $next;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}