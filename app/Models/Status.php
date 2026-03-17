<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name', 'role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
