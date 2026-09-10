<?php

namespace App\Http\Resources\WorkOrder;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListWoValidationResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $item->id ?? null,
                    'pppoe_secret' => $item->pppoe_secret ?? null,
                    'wo_date' => $item->wo_date ?? null,
                    'no_wo' => $item->no_wo ?? null,
                    'branch_name' => $item->branch_name ?? null,
                    'customer_name' => $item->customer_name ?? null,
                    'splitter' => $item->splitter ?? null,
                    'team_name' => $item->team_name ?? null,
                    'katim' => $item->katim ?? null,
                    'status' => $item->status ?? 'DRAFT',
                    'status_name' => $item->status_name ?? null,
                    'score' => isset($item->score_avg) ? number_format($item->score_avg, 1) : null,
                    'status_by' => $item->logsNewest->byUser->name ?? null,
                    'status_at' => $item->logsNewest->created_at ?? null,
                ];
            })->all(),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'links' => $this->getLinks(),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }

    protected function getLinks()
    {
        $links = [];
        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();

        // Previous link
        $links[] = [
            'url' => $this->previousPageUrl(),
            'label' => '&laquo; Previous',
            'page' => $currentPage > 1 ? $currentPage - 1 : null,
            'active' => false,
        ];

        // Page links
        for ($page = 1; $page <= $lastPage; $page++) {
            $links[] = [
                'url' => $this->url($page),
                'label' => (string)$page,
                'page' => $page,
                'active' => $page === $currentPage,
            ];
        }

        // Next link
        $links[] = [
            'url' => $this->nextPageUrl(),
            'label' => 'Next &raquo;',
            'page' => $currentPage < $lastPage ? $currentPage + 1 : null,
            'active' => false,
        ];

        return $links;
    }
}
