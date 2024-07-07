<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\Label\LabelSelectResource;
use App\Http\Resources\Priority\PriorityResource;
use App\Http\Resources\Status\StatusResource;
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
        /** @var Order $Order */
        $Order = $this;
        return [
            'id' => $Order->id,
            'uuid' => $Order->uuid,
            'subject' => $Order->subject,
            'lastReply' => new OrderReplyQuickDetailsResource($Order->OrderReplies->last()),
            'status' => new StatusResource($Order->status),
            'priority' => new PriorityResource($Order->priority),
            'branches' => new BranchSelectResource($Order->branches),
            'labels' => LabelSelectResource::collection($Order->labels),
            'user' => new UserDetailsResource($Order->user),
            'agent' => new UserDetailsResource($Order->agent),
            'closedBy' => new UserDetailsResource($Order->closedBy),
            'closed_at' => $Order->closed_at ? $Order->closed_at->toISOString() : null,
            'created_at' => $Order->created_at->toISOString(),
            'updated_at' => $Order->updated_at->toISOString()
        ];
    }
}
