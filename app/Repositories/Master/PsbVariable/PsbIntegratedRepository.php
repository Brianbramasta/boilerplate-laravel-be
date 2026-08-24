<?php

namespace App\Repositories\Master\PsbVariable;

use App\Interface\Master\PsbVariable\PsbWoStepInterface;
use App\Models\Audit\MasterDataLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Implementasi "switch" untuk koneksi langsung ke sistem BE lain, mengikuti
 * kontrak GET/PATCH {{baseUrl}}/psb-wo-steps yang diberikan tim terkait.
 *
 * BELUM diverifikasi ke server sungguhan (belum ada ERP_API_URL/ERP_API_KEY
 * nyata) — aktifkan lewat PSB_WO_STEP_DRIVER=api setelah kredensial ERP
 * tersedia, lalu uji ulang seluruh endpoint sebelum dipakai di production.
 *
 * Catatan ketidakpastian kontrak yang masih perlu dikonfirmasi ke tim BE lain:
 * - Endpoint list (GET /psb-wo-steps) tidak mengembalikan `order`/`is_active`
 *   (hanya ada di detail) — index() di bawah mengambil detail per-item untuk
 *   melengkapinya, cukup wajar untuk katalog kecil (±15 step).
 * - Kontrak saat ini hanya punya field `variables` (belum ada `default_variables`,
 *   masih didiskusikan). Karena itu, update() di bawah memetakan payload
 *   `default_component` kita ke field `variables` sebagai jembatan sementara —
 *   perlu direvisi begitu tim BE lain memastikan field `default_variables`.
 */
class PsbIntegratedRepository implements PsbWoStepInterface
{
    public function index($params = [], $perPage)
    {
        $response = $this->client()->get('/psb-wo-steps');
        $this->ensureSuccessful($response, 'Gagal mengambil data psb wo steps');

        $steps = collect($response->json('data'))
            ->when(!empty($params['search']), function ($collection) use ($params) {
                $search = strtolower($params['search']);

                return $collection->filter(function ($step) use ($search) {
                    return str_contains(strtolower($step['code']), $search)
                        || str_contains(strtolower($step['name']), $search);
                })->values();
            })
            // List tidak membawa `order`/`is_active`, jadi lengkapi lewat detail.
            ->map(fn ($step) => (object) array_merge($step, (array) $this->show((string) $step['id'])));

        $perPage = $perPage ?: 10;
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $steps->sortBy('order')->values()->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator($items, $steps->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    public function show(string $id)
    {
        $response = $this->client()->get("/psb-wo-steps/{$id}");

        if ($response->status() === 404) {
            throw new HttpException(404, 'Data psb wo step tidak ditemukan');
        }

        $this->ensureSuccessful($response, 'Gagal mengambil detail psb wo step');

        $data = $response->json('data');

        return (object) [
            'id' => $data['id'],
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'order' => $data['order'],
            'is_last' => $data['is_last'],
            'is_picture' => $data['is_picture'],
            'is_data' => $data['is_data'],
            'is_active' => $data['is_active'],
            // `default_variables` belum tersedia di kontrak — lihat catatan di atas.
            'default_component' => $data['default_variables'] ?? [],
            'extend_component' => $data['variables'] ?? [],
        ];
    }

    public function update(string $id, array $data)
    {
        $old = $this->show($id);

        $response = $this->client()->patch("/psb-wo-steps/{$id}", [
            'description' => $data['description'],
            // Jembatan sementara: lihat catatan ketidakpastian kontrak di atas.
            'variables' => $data['default_component'] ?? $old->extend_component,
        ]);

        if ($response->status() === 404) {
            throw new HttpException(404, 'Data psb wo step tidak ditemukan');
        }

        if ($response->status() === 422) {
            throw new HttpException(422, $response->json('message', 'Validasi gagal'));
        }

        $this->ensureSuccessful($response, 'Gagal mengubah data psb wo step');

        $new = $this->show($id);

        $this->logChange($new, $old);

        return $new;
    }

    protected function client()
    {
        return Http::baseUrl(config('services.erp.url'))
            ->withHeaders(['X-API-KEY' => config('services.erp.key')])
            ->acceptJson();
    }

    protected function ensureSuccessful($response, string $message): void
    {
        if (!$response->successful() || $response->json('success') === false) {
            throw new HttpException(502, $response->json('message', $message));
        }
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
