<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

protected $fillable = [

    'store_code',
    'storename',
    'phone',
    'alt_phone',
    'email',
    'alt_email',

    'address_line1',
    'address_line2',
    'city',
    'state',
    'pincode',
    'landmark',

    'contact_name',
    'contact_phone',
    'contact_email',
    'contact_whatsapp',
    'contact_address',
       // branch
    'branch_name',
    'branch_code',
    'branch_contact_name',
    'branch_contact_phone',
    'branch_contact_email',

];

  public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
