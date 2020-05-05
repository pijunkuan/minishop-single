<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'data'=>CustomerResource::collection($this->collection),
          'page'=>[
              'total' => $this->total(),
              'count' => $this->count(),
              'per_page' => $this->perPage(),
              'current_page' => $this->currentPage(),
              'total_pages' => $this->lastPage()
          ]
        ];
    }
}
