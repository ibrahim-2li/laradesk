<?php

namespace App\Http\Resources\OrderReply;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Models\Item;
use Illuminate\Http\Request;
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
            'id' => $orderItems->id,
            'user' => new UserDetailsResource($orderItems->user),
            'item' => $orderItems->item,
            'item_count' => $orderItems->item_count,
            'details' => $orderItems->details,
            'created_at' => $orderItems->created_at->toISOString(),
        ];
    }
}
