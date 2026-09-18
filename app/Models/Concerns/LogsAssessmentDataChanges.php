<?php

namespace App\Models\Concerns;

use App\Models\Audit\AssessmentDataLog;
use Illuminate\Support\Facades\Auth;

trait LogsAssessmentDataChanges
{
    public static function bootLogsAssessmentDataChanges(): void
    {
        static::created(function ($model) {
            $model->recordAssessmentDataLog('created', [], $model->attributesToArray());
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

            $model->recordAssessmentDataLog('updated', $original, $new);
        });

        static::deleted(function ($model) {
            $model->recordAssessmentDataLog('deleted', $model->attributesToArray(), []);
        });
    }

    protected function recordAssessmentDataLog(string $action, array $oldValues, array $newValues): void
    {
        AssessmentDataLog::create([
            'loggable_type' => static::class,
            'loggable_id' => $this->getKey(),
            'action' => $action,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'changed_by' => Auth::id(),
        ]);
    }
}
