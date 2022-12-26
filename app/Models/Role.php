<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * Default system roles
     */
    const ADMINISTRATOR = 'Administrator';

    const FINANCE = 'Finance';

    const STAFF = 'Staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
    ];

    // The relationships that should always be loaded.
    protected $with = ['rolePermissions.permission:id,resource,name,description'];

    // Get details of a user who created this role.
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get permissions linked with this role.
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    // Get users linked with this role.
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
