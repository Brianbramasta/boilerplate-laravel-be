<?php

namespace App\Services\Ref;

use App\Interface\Ref\RefErpInterface;
use Ramsey\Collection\Collection;

class RefService
{
    private RefErpInterface $refErpInterface;

    public function __construct(RefErpInterface $refErpInterface)
    {
        $this->refErpInterface = $refErpInterface;
    }

    /**
     * Ambil data Branch, opsional tambahkan opsi "All Branches" di awal.
     */
    public function getBranches()
    {
        $branches = $this->refErpInterface->getBranch();

        // Pastikan selalu Collection
        if (! $branches instanceof Collection) {
            $branches = collect($branches ?? []);
        }

        return $branches;
    }

    /**
     * Ambil data Katim/Tim, opsional tambahkan opsi "All Katim/Tim" di awal.
     */
    public function getKatims()
    {
        $katims = $this->refErpInterface->getKatim();

        if (! $katims instanceof Collection) {
            $katims = collect($katims ?? []);
        }

        return $katims;
    }
}
