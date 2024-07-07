<?php

namespace App\Http\Resources\OrderStatus;

use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var OrderStatus $status */
        $orderstatus = $this;
        return [
            'id' => $orderstatus->id,
            'name' => $orderstatus->name,
            'created_at' => $orderstatus->created_at->toISOString()
        ];
    }
}
