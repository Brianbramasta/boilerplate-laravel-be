<?php

namespace App\Repositories\Master\PsbVariable\Concerns;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Aturan merge untuk default_component & extend_component, dipakai bersama
 * oleh PsbJsonRepository dan PsbIntegratedRepository supaya perilaku kedua
 * "switch" tetap konsisten:
 * - default_component: hanya toggle is_active untuk key yang sudah ada.
 * - extend_component: payload = daftar penuh item yang masih ingin aktif
 *   (diff-based, bukan flag eksplisit). Key baru otomatis ditambahkan; key
 *   lama yang sebelumnya terlihat tapi tidak disebut lagi otomatis soft
 *   delete (deleted_at diisi, item tetap tersimpan bukan dihapus).
 */
trait MergesPsbStepComponents
{
    protected function applyDefaultComponentUpdates(array $existing, array $updates): array
    {
        $items = collect($existing)->map(fn ($item) => (array) $item);

        foreach ($updates as $update) {
            $matched = false;

            $items = $items->map(function ($item) use ($update, &$matched) {
                if ($item['key'] === $update['key']) {
                    $matched = true;
                    $item['is_active'] = $update['is_active'];
                }

                return $item;
            });

            if (!$matched) {
                throw new HttpException(422, "Komponen default '{$update['key']}' tidak ditemukan.");
            }
        }

        return $items->values()->all();
    }

    protected function applyExtendComponentUpdates(array $existing, array $updates): array
    {
        $items = collect($existing)->map(fn ($item) => (array) $item)->keyBy('key');

        $visibleKeys = $items->reject(fn ($item) => !empty($item['deleted_at']))->keys();
        $incomingKeys = collect($updates)->pluck('key');

        // Upsert setiap item yang dikirim (baru atau lama, dan "hidupkan"
        // lagi kalau kebetulan key-nya sama dengan yang pernah di-soft-delete).
        foreach ($updates as $update) {
            $key = $update['key'];
            $current = $items->get($key, [
                'key' => $key,
                'value' => null,
                'is_active' => true,
                'deleted_at' => null,
            ]);

            $current['value'] = array_key_exists('value', $update) ? $update['value'] : $current['value'];
            $current['is_active'] = $update['is_active'];
            $current['deleted_at'] = null;

            $items->put($key, $current);
        }

        // Item yang sebelumnya terlihat tapi tidak disebut lagi di payload -> soft delete.
        foreach ($visibleKeys->diff($incomingKeys) as $key) {
            $item = $items->get($key);
            $item['deleted_at'] = now()->toISOString();
            $items->put($key, $item);
        }

        return $items->values()->all();
    }

    protected function withVisibleExtendComponent(object $step): object
    {
        $step = clone $step;
        $step->extend_component = collect((array) ($step->extend_component ?? []))
            ->map(fn ($item) => (array) $item)
            ->reject(fn ($item) => !empty($item['deleted_at']))
            ->values()
            ->all();

        return $step;
    }
}
