<?php
namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        // CREATE
        static::created(function ($model) {
            self::log('created', $model);
        });

        // UPDATE (قبل التعديل)
       static::updating(function ($model) {
    self::log('updated', $model, $model->getOriginal());
    });

        // DELETE
        static::deleted(function ($model) {
            self::log('deleted', $model);
        });
    }

    protected static function log($action, $model, $oldValues = null)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => class_basename($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $model->getAttributes(),
            'ip' => request()->ip(),
        ]);
    }
}