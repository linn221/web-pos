<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo ?? config('info.default_brand_photo'),
            'company' => $this->company,
            'agent' => $this->agent,
            'phone_no' => $this->phone_no,
            // @fix
            'products' => $this->products->pluck('name')
            // 'products' => ProductResource::collection($this->products),
            // 'description' => $this->description,
            // 'information' => $this->information,
        ];
    }
}
