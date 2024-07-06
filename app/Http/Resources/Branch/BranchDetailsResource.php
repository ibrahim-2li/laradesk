<?php

namespace App\Http\Resources\Branch;

use App\Http\Resources\User\UserDetailsResource;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchDetailsResource extends JsonResource
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
            'all_agents' => (bool) $branche->all_agents,
            'public' => (bool) $branche->public,
            'agents' => !$branche->all_agents ? UserDetailsResource::collection($branche->agent->take(5)) : []
        ];
    }
}
