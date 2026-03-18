<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tailor()
    {
        return $this->hasOne(Tailor::class);
    }

    public function salesAssociate()
    {
        return $this->hasOne(SalesAssociate::class);
    }

public function projects()
{
    return $this->belongsToMany(Project::class);
}
public function customer()
{
    return $this->hasOne(Customer::class);
}
}
