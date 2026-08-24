<?php

namespace App\Repositories\Master\PsbVariable;

use App\Interface\Master\PsbVariable\PsbWoStepInterface;
use App\Models\Audit\MasterDataLog;
use App\Repositories\Master\PsbVariable\Concerns\MergesPsbStepComponents;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Implementasi "switch" lokal untuk data PSB WO Step: dibaca/ditulis sebagai
 * satu file JSON di storage (bukan tabel database), supaya tidak ada data
 * yang perlu dibersihkan dari DB begitu nanti pindah ke PsbIntegratedRepository.
 *
 * Titik switch-nya ada di config('services.psb_wo_step.driver'), dipakai oleh
 * AppServiceProvider untuk menentukan repository mana yang di-bind ke
 * PsbWoStepInterface. Aturan merge default_component/extend_component ada di
 * MergesPsbStepComponents (dipakai bersama PsbIntegratedRepository).
 */
class PsbJsonRepository implements PsbWoStepInterface
{
    use MergesPsbStepComponents;

    protected string $path = 'psb-wo-steps.json';

    public function index($params = [], $perPage)
    {
        $steps = $this->readSteps();

        if (!empty($params['search'])) {
            $search = strtolower($params['search']);
            $steps = $steps->filter(function ($step) use ($search) {
                return str_contains(strtolower($step->code), $search)
                    || str_contains(strtolower($step->name), $search);
            })->values();
        }

        $steps = $steps->sortBy('order')->values()->map(fn ($step) => $this->withVisibleExtendComponent($step));

        $perPage = $perPage ?: 10;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $steps->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($items, $steps->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    public function show(string $id)
    {
        return $this->withVisibleExtendComponent($this->findOrFail($id));
    }

    public function update(string $id, array $data)
    {
        $steps = $this->readSteps();
        $index = $steps->search(fn ($step) => (int) $step->id === (int) $id);

        if ($index === false) {
            throw new HttpException(404, 'Data psb wo step tidak ditemukan');
        }

        $step = $steps[$index];
        $old = clone $step;

        $step->description = $data['description'];

        if (array_key_exists('default_component', $data)) {
            $step->default_component = $this->applyDefaultComponentUpdates(
                (array) ($step->default_component ?? []),
                $data['default_component']
            );
        }

        if (array_key_exists('extend_component', $data)) {
            $step->extend_component = $this->applyExtendComponentUpdates(
                (array) ($step->extend_component ?? []),
                $data['extend_component']
            );
        }

        $step->updated_at = now()->toISOString();

        $steps[$index] = $step;
        $this->writeSteps($steps);

        $this->logChange($step, $old);

        return $this->withVisibleExtendComponent($step);
    }

    protected function findOrFail(string $id): object
    {
        $step = $this->readSteps()->first(fn ($step) => (int) $step->id === (int) $id);

        if (!$step) {
            throw new HttpException(404, 'Data psb wo step tidak ditemukan');
        }

        return $step;
    }

    protected function readSteps(): Collection
    {
        if (!Storage::exists($this->path)) {
            return collect();
        }

        return collect(json_decode(Storage::get($this->path) ?: '[]', false));
    }

    protected function writeSteps(Collection $steps): void
    {
        Storage::put(
            $this->path,
            json_encode($steps->values()->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    protected function logChange(object $new, object $old): void
    {
        MasterDataLog::create([
            'loggable_type' => 'psb_wo_step',
            'loggable_id' => (string) $new->id,
            'action' => 'updated',
            'old_values' => [
                'description' => $old->description,
                'default_component' => $old->default_component,
                'extend_component' => $old->extend_component,
            ],
            'new_values' => [
                'description' => $new->description,
                'default_component' => $new->default_component,
                'extend_component' => $new->extend_component,
            ],
            'changed_by' => Auth::id(),
        ]);
    }
}
