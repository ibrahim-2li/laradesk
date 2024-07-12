<?php

namespace App\Http\Resources\Branch;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
        $branch = $this;
        return [
            'id' => $branch->id,
            'name' => $branch->name,
            'all_agents' => $branch->all_agents,
            'public' => $branch->public,
            'agents' => $branch->agent()->pluck('id')
        ];
    }
}
