<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'assigned_user_id',
        'tailor_id',
        'sales_associate_id',
        'due_date',
        'due_time',
        'priority',
        'status',
        'requested_status',
    ];

    public function tailor()
    {
        return $this->belongsTo(Tailor::class, 'tailor_id');
    }

    public function salesAssociate()
    {
        return $this->belongsTo(SalesAssociate::class, 'sales_associate_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // ✅ MAIN assigned user (existing column: assigned_user_id)
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // ✅ MULTIPLE assigned users (pivot: task_user)
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}
