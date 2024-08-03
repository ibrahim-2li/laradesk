<?php

namespace App\Http\Resources\OrderReply;

use App\Http\Resources\File\FileResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfirmItemsDetailsResource extends JsonResource
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
        $confirmItems = $this;
        return [
            'id' => $confirmItems->id,
            'user' => new UserDetailsResource($confirmItems->user),
            'item' => $confirmItems->item,
            'item_count' => $confirmItems->item_count,
            'details' => $confirmItems->details,
            'created_at' => $confirmItems->created_at->toISOString(),
        ];
    }
}
