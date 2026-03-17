<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'project_id',
        'payment_id',
        'order_id',
        'invoice_no',
        'grand_total',
        'created_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payment()
    {
        return $this->belongsTo(ReceivedPayment::class, 'payment_id');
    }
}
