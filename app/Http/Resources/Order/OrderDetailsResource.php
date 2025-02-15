<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Resources\Status\StatusResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\OrderReply\OrderItemsDetailsResource;
use App\Http\Resources\OrderReply\OrderReplyDetailsResource;
use App\Http\Resources\OrderReply\ConfirmItemsDetailsResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Order $order */
        $order = $this;
        return [
            'id' => $order->id,
            'uuid' => $order->uuid,
            'subject' => $order->subject,
            'status'  => new StatusResource($order->status),
            'orders_status_id' => $order->orders_status_id,
            'branches' => new BranchSelectResource($order->branches),
            'branches_id' => $order->branches_id,
            'created_at' => $order->created_at->toISOString(),
            'updated_at' => $order->updated_at->toISOString(),
            'orderItems' => OrderItemsDetailsResource::collection($order->items()->orderByDesc('created_at')->get()),
        ];
    }
}
