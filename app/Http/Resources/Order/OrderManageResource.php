<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\Label\LabelSelectResource;
use App\Http\Resources\Priority\PriorityResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Http\Resources\OrderReply\OrderReplyDetailsResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderManageResource extends JsonResource
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
            'statuso' => new OrderStatusResource($order->statuso),
            'order_status_id' => $order->order_status_id,
            'branches' => new BranchSelectResource($order->branches),
            'branches_id' => $order->branches_id,
            'priority' => new PriorityResource($order->priority),
            'priority_id' => $order->priority_id,
            'labels' => LabelSelectResource::collection($order->labels),
            'user' => new UserDetailsResource($order->user),
            'user_id' => $order->user_id,
            'agent' => new UserDetailsResource($order->agent),
            'agent_id' => $order->agent_id,
            'closedBy' => new UserDetailsResource($order->closedBy),
            'closed_by' => $order->closed_by,
            'closed_at' => $order->closed_at ? $order->closed_at->toISOString() : null,
            'created_at' => $order->created_at->toISOString(),
            'updated_at' => $order->updated_at->toISOString(),
            'orderReplies' => OrderReplyDetailsResource::collection($order->orderReplies()->orderByDesc('created_at')->get()),

        ];
    }
}
