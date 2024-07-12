<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\Label\LabelSelectResource;
use App\Http\Resources\Priority\PriorityResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Http\Resources\OrderReply\OrderReplyQuickDetailsResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
            'lastReply' => new OrderReplyQuickDetailsResource($order->orderReplies->last()),
            'status' => new OrderStatusResource($order->status),
            'priority' => new PriorityResource($order->priority),
            'branches' => new BranchSelectResource($order->branches),
            'labels' => LabelSelectResource::collection($order->labels),
            'user' => new UserDetailsResource($order->user),
            'agent' => new UserDetailsResource($order->agent),
            'closedBy' => new UserDetailsResource($order->closedBy),
            'closed_at' => $order->closed_at ? $order->closed_at->toISOString() : null,
            'created_at' => $order->created_at->toISOString(),
            'updated_at' => $order->updated_at->toISOString()
        ];
    }
}
