<?php

namespace App\Repositories\Ref;

use App\Interface\Ref\RefErpInterface;
use Illuminate\Support\Collection;

class RefJsonRepository implements RefErpInterface
{
    /**
     * Path relatif terhadap root disk "local".
     *
     * - Laravel 11+: path cukup "branch.json" (root: storage/app/private)
     * - Laravel <=10: path "private/branch.json" (root: storage/app)
     * Deteksi otomatis di method resolvePath().
     */
    protected string $branchFile = 'branch.json';
    protected string $katimFile = 'katim.json';

    public function getBranch(): Collection
    {
        return $this->readJson($this->branchFile);
    }

    public function getKatim(): Collection
    {
        return $this->readJson($this->katimFile);
    }

    /**
     * Baca file JSON dan kembalikan sebagai Collection.
     */
    protected function readJson(string $filename): Collection
    {
        $path = $this->resolvePath($filename);

        if ($path === null || !file_exists($path)) {
            return collect();
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return collect();
        }

        $decoded = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE || $decoded === null) {
            return collect();
        }

        return collect($decoded);
    }

    /**
     * Cari lokasi file JSON yang benar, baik untuk Laravel 10 maupun 11+.
     * 
     * Urutan:
     * 1. storage/app/private/{filename}   (Laravel 11+)
     * 2. storage/app/{filename}           (root disk sudah private)
     * 
     * Return: full path file atau null.
     */
    protected function resolvePath(string $filename): ?string
    {
        $candidates = [
            storage_path('app/private/' . $filename),
            storage_path('app/' . $filename),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
