<?php

namespace App\Traits;

use App\Models\Scopes\CompanyScope;

trait HasCompanyScope{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
    }
}