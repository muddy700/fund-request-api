<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, Uuids, SoftDeletes;

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
        'creayted_by',
    ];

    /**
     * Get details of a user who created this role.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}