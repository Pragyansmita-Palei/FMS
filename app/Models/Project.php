<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [

        'customer_id',
        'project_code',
         'order_id',
        'project_name',
        'address',
        'sales_associate_id',
        'tailor_id',
        'interior_id',
        'status',
        'final_amount',
        'due_amount',
        'project_deadline',
        'project_requirement',
        'project_start_date',
        'estimated_end_date',
        'priority',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function measurements()
{
    return $this->hasMany(\App\Models\ProjectMeasurement::class);
}
public function materials()
{
    return $this->hasMany(Material::class);
}

public function tasks()
{
    return $this->hasMany(Task::class);
}

// ✅ SALES ASSOCIATE (FIX)
    public function salesAssociate()
    {
        return $this->belongsTo(SalesAssociate::class, 'sales_associate_id');
    }
     // ✅ TAILOR
    public function tailor()
    {
        return $this->belongsTo(Tailor::class, 'tailor_id');
    }
      public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'measurements',     // pivot table
            'project_id',
            'area_id'
        )->distinct();
    }

    public function quotationItems()
{
    return $this->hasMany(QuotationItem::class);
}
public function invoices()
{
    return $this->hasMany(Invoice::class);
}
public function receivedPayments()
{
    return $this->hasMany(ReceivedPayment::class);
}

public function quotations()
{
    return $this->hasMany(\App\Models\Quotation::class);
}

public function interior()
{
    return $this->belongsTo(Interior::class);
}

public function users()
{
    return $this->belongsToMany(User::class);
}
}

