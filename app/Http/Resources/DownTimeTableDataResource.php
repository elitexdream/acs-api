<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DownTimeTableDataResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'page' => $this->currentPage(),
                'itemsPerPage' => $this->perPage(),
                'pageStart' => 1,
                'pageStop' => $this->count(),
                'totalItems' => $this->total(),
                'pageCount' => $this->count(),
            ],
        ];
    }
}
