<?php

namespace App\Traits;

trait GeneralTrait
{
    /**
     * Common utility functions.
     * Ported from perfume_admin.
     */
    public function checkLocale()
    {
        return app()->getLocale();
    }

    // Add other generic utilities as needed from the reference
}
