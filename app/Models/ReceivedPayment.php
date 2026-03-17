<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivedPayment extends Model
{
    protected $fillable = [
        'project_id',
        'order_id',
        'amount',
        'payment_mode',
        'transaction_number',
        'payment_date',
        'remarks',
        'created_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }
public function invoice()
{
    return $this->hasOne(Invoice::class, 'payment_id');
}

}
