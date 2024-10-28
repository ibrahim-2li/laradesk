<?php

namespace App\Http\Resources\Brand;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Brand $brand */
        $brand = $this;
        return [
            'id' => $brand->id,
            'name' => $brand->name,
            'created_at' => $brand->created_at->toISOString()
        ];
    }
}
