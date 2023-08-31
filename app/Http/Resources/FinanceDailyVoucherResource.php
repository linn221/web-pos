<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinanceDailyVoucherResource extends JsonResource
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
            'voucher_number' => $this->voucher_number,
            'time' => $this->created_at->format(' h:m:i A'),
            'item_count' => $this->voucher_records->count(),
            'cash' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'created_at' => $this->created_at
        ];
    }
}
