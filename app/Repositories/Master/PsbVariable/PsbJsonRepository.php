<?php

namespace App\Repositories\Master\PsbVariable;

use App\Interface\Master\PsbVariable\PsbWoStepInterface;
use App\Models\Audit\MasterDataLog;
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
 * PsbWoStepInterface.
 */
class PsbJsonRepository implements PsbWoStepInterface
{
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

        $steps = $steps->sortBy('order')->values();

        $perPage = $perPage ?: 10;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $steps->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($items, $steps->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    public function show(string $id)
    {
        $step = $this->readSteps()->first(fn ($step) => (int) $step->id === (int) $id);

        if (!$step) {
            throw new HttpException(404, 'Data psb wo step tidak ditemukan');
        }

        return $step;
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

        // Selaras dengan perilaku API eksternal: array component dikirim penuh
        // dan MENGGANTIKAN (bukan menggabungkan) isi sebelumnya.
        if (array_key_exists('default_component', $data)) {
            $step->default_component = $data['default_component'];
        }

        $step->updated_at = now()->toISOString();

        $steps[$index] = $step;
        $this->writeSteps($steps);

        $this->logChange($step, $old);

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
            ],
            'new_values' => [
                'description' => $new->description,
                'default_component' => $new->default_component,
            ],
            'changed_by' => Auth::id(),
        ]);
    }
}
