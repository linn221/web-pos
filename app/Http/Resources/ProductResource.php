<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sale_price' => $this->sale_price,
            'total_stock' => $this->total_stock,
            'brand_name' => $this->brand->name,
            'brand_id' => $this->brand->id,
            'category_name' => $this->category->name,
            'category_id' => $this->category->id,
            'unit' => $this->unit,
            'photo' => $this->photo ?? config('info.default_product_photo'),
            'more_information' => $this->more_information,
            'create_date' => $this->created_at->format('d-m-y'),
            'update_date' => $this->updated_at->format('d-m-y'),
        ];
    }
}
