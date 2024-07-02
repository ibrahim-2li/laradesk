<?php

namespace App\Http\Resources\OrderReply;

use App\Models\OrderReply;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class OrderReplyQuickDetailsResource extends JsonResource
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
        $OrderReply = $this;
        return [
            'id' => $OrderReply->id,
            'body' => Str::words(strip_tags(str_ireplace(['<br />', '<br>', '<br/>'], ' ', $OrderReply->body)), 50),
            'created_at' => $OrderReply->created_at->toISOString(),
        ];
    }
}
