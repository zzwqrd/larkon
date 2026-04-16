<?php

namespace App\Services;

class ActivityLogService
{
    protected $userable;

    public function __construct($user = null)
    {
        if ($user) {
            $this->userable = $user;
        } elseif (auth('admin')->check()) {
            $this->userable = auth('admin')->user();
        }
    }

    /**
     * Log a professional administrative action.
     */
    public function log($translationKey, $data = null)
    {
        if ($this->userable) {
            $this->userable->activityLogs()->create([
                'key' => $translationKey,
                'data' => $data ? serialize($data) : null,
            ]);
        }
    }

    /**
     * Standardized text output for logs.
     */
    public static function logText($activityLog)
    {
        return __($activityLog->key, isset($activityLog->data) ? unserialize($activityLog->data) : []);
    }
}
