<?php

namespace App\Http\Resources\Label;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandSelectResource extends JsonResource
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
        ];
    }
}
