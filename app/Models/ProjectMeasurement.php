<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'product_group',
        'reference',
        'measurement',
        'width',
        'height',
        'quantity',
    ];

    // Relationship
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
