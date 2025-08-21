<?php

namespace Kenny\AuditLog\Src\Traits;

use Illuminate\Database\Eloquent\Model;
use YourVendor\AuditLog\Models\AuditLog; // Reference your package's AuditLog model

trait Auditable
{
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::logAudit($model, 'created');
        });

        static::updated(function (Model $model) {
            self::logAudit($model, 'updated');
        });

        static::deleted(function (Model $model) {
            self::logAudit($model, 'deleted');
        });
    }

    /**
     * Log the audit event for the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event
     * @return void
     */
    protected static function logAudit(Model $model, string $event)
    {
        $oldValues = ($event === 'updated') ? $model->getOriginal() : null;
        $newValues = ($event === 'deleted') ? null : $model->getAttributes();
        $changedAttributes = [];

        if ($event === 'updated') {
            $changedAttributes = array_diff_assoc($newValues, $oldValues);
            // Exclude common attributes that change but might not be important to log
            $attributesToExclude = ['updated_at', 'created_at', 'id'];
            $changedAttributes = array_filter($changedAttributes, function ($key) use ($attributesToExclude) {
                return !in_array($key, $attributesToExclude);
            }, ARRAY_FILTER_USE_KEY);
            if (empty($changedAttributes)) {
                return; // No meaningful changes to log
            }
        }

        $message = match ($event) {
            'created' => 'Record created.',
            'updated' => 'Columns updated: ' . implode(', ', array_keys($changedAttributes)),
            'deleted' => 'Record deleted.',
            default => 'Model ' . $event,
        };

        AuditLog::create([
            'auditable_id' => $model->id,
            'auditable_type' => get_class($model),
            'user_id' => auth()->check() ? auth()->id() : null, // Use auth()->id() for user ID
            'event' => $event,
            'message' => $message,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
        ]);
    }
}
