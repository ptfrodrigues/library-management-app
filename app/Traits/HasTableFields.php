<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait HasTableFields
{
    public static function getTableFields()
    {
        return static::$tableFields ?? [];
    }

    public static function getAllFields()
    {
        return array_merge(array_diff(Schema::getColumnListing((new static)->getTable())));
    }
}