<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use Illuminate\Http\Request;
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
        /** @var Order $Order */
        $order = $this;
        return [
            'id' => $order->id,
            'uuid' => $order->uuid,
            'subject' => $order->subject,
            'branches' => new BranchSelectResource($order->branches),
            'branches_id' => $order->branches_id,
            'created_at' => $order->created_at->toISOString(),
            'updated_at' => $order->updated_at->toISOString(),
            'orderReplies' => OrderReplyDetailsResource::collection($order->orderReplies()->orderByDesc('created_at')->get()),
            'orderItems' => OrderItemsDetailsResource::collection($order->orderItems()->orderByDesc('created_at')->get()),
            'confirmItems' => ConfirmItemsDetailsResource::collection($order->confirmItems()->orderByDesc('created_at')->get()),


        ];
    }
}
