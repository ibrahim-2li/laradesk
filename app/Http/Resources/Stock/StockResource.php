<?php

namespace App\Http\Resources\Stock;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Stock $stock */
        $stock = $this;
        return [
            'id' => $stock->id,
            'name' => $stock->name,
            'brand_id' => $stock->brand_id,
            'quantity' => $stock->quantity,
            'created_at' => $stock->created_at->toISOString(),
            'updated_at' => $stock->updated_at->toISOString()
        ];
    }
}
