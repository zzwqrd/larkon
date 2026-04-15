<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Search scope for AJAX filtering
     */
    public function scopeSearch($query, $request)
    {
        return $query->where(function ($q) use ($request) {
            if ($request->search) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            }
        });
    }

    /**
     * Relationship with Permissions
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Relationship with Admins
     */
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
