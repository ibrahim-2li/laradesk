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
        /** @var Branch $branch */
        $branch = $this;
        return [
            'id' => $branch->id,
            'name' => $branch->name,
            'all_agents' => (bool) $branch->all_agents,
            'public' => (bool) $branch->public,
            'agents' => !$branch->all_agents ? UserDetailsResource::collection($branch->agent->take(5)) : []
        ];
    }
}
