<?php

namespace App\Models\Concerns;

use App\Models\Audit\MasterDataLog;
use Illuminate\Support\Facades\Auth;

trait LogsMasterDataChanges
{
    public static function bootLogsMasterDataChanges(): void
    {
        static::created(function ($model) {
            $model->recordMasterDataLog('created', [], $model->attributesToArray());
        });

        static::updated(function ($model) {
            $changedKeys = array_diff(array_keys($model->getChanges()), ['updated_at']);

            if (empty($changedKeys)) {
                return;
            }

            // attributesToArray() dipakai (bukan getChanges() mentah) supaya kolom
            // ber-cast (mis. json/array) tersimpan dalam bentuk yang sama seperti
            // getOriginal(), bukan string JSON mentah.
            $current = $model->attributesToArray();

            $original = [];
            $new = [];
            foreach ($changedKeys as $key) {
                $original[$key] = $model->getOriginal($key);
                $new[$key] = $current[$key] ?? null;
            }

            $model->recordMasterDataLog('updated', $original, $new);
        });

        static::deleted(function ($model) {
            $model->recordMasterDataLog('deleted', $model->attributesToArray(), []);
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
