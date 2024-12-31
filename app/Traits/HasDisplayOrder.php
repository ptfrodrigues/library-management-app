<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasDisplayOrder
{
    public static function bootHasDisplayOrder()
    {
        static::creating(function ($model) {
            $model->display_order = static::max('display_order') + 1;
        });

        static::deleted(function ($model) {
            static::where('display_order', '>', $model->display_order)
                  ->decrement('display_order');
        });
    }

    public function moveOrderUp()
    {
        DB::transaction(function () {
            $previousItem = static::where('display_order', '<', $this->display_order)
                                  ->orderBy('display_order', 'desc')
                                  ->first();

            if ($previousItem) {
                $this->swapOrderWith($previousItem);
            }
        });
    }

    public function moveOrderDown()
    {
        DB::transaction(function () {
            $nextItem = static::where('display_order', '>', $this->display_order)
                              ->orderBy('display_order', 'asc')
                              ->first();

            if ($nextItem) {
                $this->swapOrderWith($nextItem);
            }
        });
    }

    protected function swapOrderWith($otherModel)
    {
        $thisOrder = $this->display_order;
        $otherOrder = $otherModel->display_order;

        $this->update(['display_order' => $otherOrder]);
        $otherModel->update(['display_order' => $thisOrder]);
    }
}

