<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait VisibleFields
{
    public static function getVisibleFields()
    {
        return static::$visibleFields ?? self::getAllFields();
    }

    public static function getAllFields()
    {
        return Schema::getColumnListing((new static)->getTable());
    }
}
