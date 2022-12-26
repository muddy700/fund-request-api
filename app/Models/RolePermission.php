<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    // The attributes that are mass assignable.   
    protected $fillable = [
        'role_id',
        'permission_id',
        'created_by',
    ];

    // Get details of a user who created this role.
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get details of a role associated with this record.
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Get details of a permission associated with this record.
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
