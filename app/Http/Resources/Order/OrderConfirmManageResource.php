<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDetailsResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Label\LabelSelectResource;
use App\Http\Resources\Priority\PriorityResource;
use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\OrderStatus\OrderStatusResource;
use App\Http\Resources\OrderReply\OrderItemsDetailsResource;
use App\Http\Resources\OrderReply\OrderReplyDetailsResource;
use App\Http\Resources\OrderReply\ConfirmItemsDetailsResource;

class OrderConfirmManageResource extends JsonResource
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

            'status' => new OrderStatusResource($order->status),
            'orders_status_id' => $order->orders_status_id,
            'branches' => new BranchSelectResource($order->branches),
            'user' => new UserDetailsResource($order->user),
            'user_id' => $order->user_id,
            'closedBy' => new UserDetailsResource($order->closedBy),
            'closed_by' => $order->closed_by,
            'closed_at' => $order->closed_at ? $order->closed_at->toISOString() : null,
            'created_at' => $order->created_at->toISOString(),
            'updated_at' => $order->updated_at->toISOString(),
            'confirmItems' => ConfirmItemsDetailsResource::collection($order->confirmItems()->orderByDesc('created_at')->get()),
        ];
    }
}
