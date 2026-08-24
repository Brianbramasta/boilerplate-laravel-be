<?php

namespace App\Models\Concerns;

use App\Models\Audit\MasterDataLog;
use Illuminate\Support\Facades\Auth;

trait LogsMasterDataChanges
{
    public static function bootLogsMasterDataChanges(): void
    {
        static::created(function ($model) {
            $model->recordMasterDataLog('created', [], $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if (empty($changes)) {
                return;
            }

            $original = [];
            foreach (array_keys($changes) as $key) {
                $original[$key] = $model->getOriginal($key);
            }

            $model->recordMasterDataLog('updated', $original, $changes);
        });

        static::deleted(function ($model) {
            $model->recordMasterDataLog('deleted', $model->getAttributes(), []);
        });
    }

    protected function recordMasterDataLog(string $action, array $oldValues, array $newValues): void
    {
        MasterDataLog::create([
            'loggable_type' => static::class,
            'loggable_id' => $this->getKey(),
            'action' => $action,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'changed_by' => Auth::id(),
        ]);
    }
}
