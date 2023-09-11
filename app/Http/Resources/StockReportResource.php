<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->total_stock > 10) {
            $stock_level = 'In Stock';
        } elseif ($this->total_stock <= 0) {
            $stock_level = "Out Of Stock";
        } else {
            $stock_level = "Low Stock";
        };
        return [


            'id' => $this->id,
            'name' => $this->name,
            'branc' => $this->brand->name,
            'unit' => $this->unit,
            'sale_price' => $this->sale_price,
            'total_stock' => $this->total_stock,
            'stock_level' => $stock_level

        ];
    }
}
