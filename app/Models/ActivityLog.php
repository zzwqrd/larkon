<?php

namespace App\Models;

use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'data',
    ];

    /**
     * Get the descriptive text for the activity log.
     */
    public function getActivityAttribute()
    {
        return ActivityLogService::logText($this);
    }

    /**
     * Polymorphic relationship to the actor (Admin, User, etc.)
     */
    public function userable()
    {
        return $this->morphTo('userable');
    }

    /**
     * Professional date formatting matching reference project
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('Y MMMM D - h:mm a');
    }
}
