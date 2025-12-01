<?php

namespace App\Http\Resources\OrderReply;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Stock\StockResource;
use App\Http\Resources\User\UserDetailsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemsDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var OrderReply $OrderReply */
        $orderItems = $this;
        return [
            'order_id' => $orderItems->order_id,
            'stock_id' => $orderItems->stock->id,
            'name' => $orderItems->stock->name,
            'quantity' => $orderItems->quantity,
            //'details' => $orderItems->details,
            'created_at' => $orderItems->created_at->toISOString(),
        ];
    }
}
