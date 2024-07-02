<?php

namespace App\Http\Resources\OrderReply;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Models\OrderReply;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderReplyDetailsResource extends JsonResource
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
        $orderReply = $this;
        return [
            'id' => $orderReply->id,
            'user' => new UserDetailsResource($orderReply->user),
            'body' => preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $orderReply->body),
            'created_at' => $orderReply->created_at->toISOString(),
            'attachments' => FileResource::collection($orderReply->orderAttachments)
        ];
    }
}
