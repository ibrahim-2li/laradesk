<?php

namespace App\Http\Resources\Branch;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Branch $branche */
        $branche = $this;
        return [
            'id' => $branche->id,
            'name' => $branche->name,
        ];
    }
}
