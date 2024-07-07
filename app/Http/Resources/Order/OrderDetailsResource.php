<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Branch\BranchSelectResource;
use App\Http\Resources\OrderReply\OrderReplyDetailsResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $Order = $this;
        return [
            'id' => $Order->id,
            'uuid' => $Order->uuid,
            'subject' => $Order->subject,
            'branches' => new BranchSelectResource($Order->branches),
            'branches_id' => $Order->branches_id,
            'created_at' => $Order->created_at->toISOString(),
            'updated_at' => $Order->updated_at->toISOString(),
            'OrderReplies' => OrderReplyDetailsResource::collection($Order->OrderReplies()->orderByDesc('created_at')->get()),
        ];
    }
}
