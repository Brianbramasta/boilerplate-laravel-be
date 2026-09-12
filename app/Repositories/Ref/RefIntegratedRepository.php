<?php

namespace App\Repositories\Ref;

use App\Interface\Ref\RefErpInterface;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * RefIntegratedRepository: implementasi RefErpInterface, mengambil data dari ERP API.
 */
class RefIntegratedRepository implements RefErpInterface
{
    public function getBranch()
    {
        $response = $this->client()->get('/branches');
        $this->ensureSuccessful($response, 'Gagal mengambil data Branch');
        return collect($response->json('data') ?: []);
    }

    public function getKatim()
    {
        $response = $this->client()->get('/technician-teams');
        $this->ensureSuccessful($response, 'Gagal mengambil data Katim');
        return collect($response->json('data') ?: []);
    }

    protected function client()
    {
        return Http::baseUrl(config('services.erp.url'))
            ->withHeaders(['X-API-KEY' => config('services.erp.key')])
            ->acceptJson();
    }

    protected function ensureSuccessful($response, string $message): void
    {
        if (!$response->successful() || ($response->json('success') === false && !is_null($response->json('success')))) {
            throw new HttpException(502, $response->json('message', $message));
        }
    }
}
