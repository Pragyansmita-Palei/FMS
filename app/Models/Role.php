<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }
}
