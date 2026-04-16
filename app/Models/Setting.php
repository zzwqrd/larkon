<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory, UploadTrait;

    protected $fillable = [
        'id_key',
        'key_value',
    ];

    /**
     * Get all settings as an associative array with caching.
     */
    public static function getAll()
    {
        return Cache::rememberForever('settings', function () {
            return self::pluck('key_value', 'id_key')->all();
        });
    }

    /**
     * Helper to get a specific setting value.
     */
    public static function val($key, $default = null)
    {
        $settings = self::getAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Boot function to clear cache on update.
     */
    protected static function boot()
    {
        parent::boot();
        static::updated(function () {
            Cache::forget('settings');
        });
        static::created(function () {
            Cache::forget('settings');
        });
    }
}
