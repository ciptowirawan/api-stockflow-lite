<?php

namespace App;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::creating(function ($model) {
           if (auth()->check()) {
                if ($model->isFillable('created_by')) {
                    $model->created_by = auth()->id();
                }
                if ($model->isFillable('updated_by')) {
                    $model->updated_by = auth()->id();
                }
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                if ($model->isFillable('updated_by')) {
                    $model->updated_by = auth()->id();
                }
            }
        });
    }
}
