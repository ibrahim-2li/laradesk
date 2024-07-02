<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Department\DepartmentSelectResource;
use App\Http\Resources\Label\LabelSelectResource;
use App\Http\Resources\Priority\PriorityResource;
use App\Http\Resources\Status\StatusResource;
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
        $Order = $this;
        return [
            'id' => $Order->id,
            'uuid' => $Order->uuid,
            'subject' => $Order->subject,
            'status' => new StatusResource($Order->status),
            'status_id' => $Order->status_id,
            'priority' => new PriorityResource($Order->priority),
            'priority_id' => $Order->priority_id,
            'department' => new DepartmentSelectResource($Order->department),
            'department_id' => $Order->department_id,
            'labels' => LabelSelectResource::collection($Order->labels),
            'user' => new UserDetailsResource($Order->user),
            'user_id' => $Order->user_id,
            'agent' => new UserDetailsResource($Order->agent),
            'agent_id' => $Order->agent_id,
            'closedBy' => new UserDetailsResource($Order->closedBy),
            'closed_by' => $Order->closed_by,
            'closed_at' => $Order->closed_at ? $Order->closed_at->toISOString() : null,
            'created_at' => $Order->created_at->toISOString(),
            'updated_at' => $Order->updated_at->toISOString(),
            'OrderReplies' => OrderReplyDetailsResource::collection($Order->OrderReplies()->orderByDesc('created_at')->get()),
        ];
    }
}
