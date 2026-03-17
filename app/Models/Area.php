<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active'
    ];

  public function measurements() {
    return $this->hasMany(Measurement::class)->orderBy('id');
}
}
