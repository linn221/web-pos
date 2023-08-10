<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'net_total' => $this->net_total,
            'count' => $this->records->count()
        ];
    }
}
