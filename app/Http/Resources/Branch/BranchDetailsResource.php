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
        /** @var Branch $branches */
        $branches = $this;
        return [
            'id' => $branches->id,
            'name' => $branches->name,
            'all_agents' => (bool) $branches->all_agents,
            'public' => (bool) $branches->public,
            'agents' => !$branches->all_agents ? UserDetailsResource::collection($branches->agent->take(5)) : []
        ];
    }
}
