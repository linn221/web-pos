<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomSalesOverviewResoruce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'voucher' => $this->voucher_number,
            'time' => $this->created_at->format('h:m A'),
            'cash' => $this->total,
            'tax' => $this->tax,
            'total' => $this->net_total
        ];
    }
}
