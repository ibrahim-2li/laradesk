<?php

namespace App\Http\Resources\Stock;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Stock $stoc */
        $stoc = $this;
        return [
            'id' => $stoc->id,
            'name' => $stoc->name,
            'stock_id' => $stoc->stock_id,
            'created_at' => $stoc->created_at->toISOString(),
            'updated_at' => $stoc->updated_at->toISOString()
        ];
    }
}
