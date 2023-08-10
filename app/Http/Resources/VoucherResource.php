<?php

namespace App\Http\Resources;

use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            // 'total' => $this->total,
            'net_total' => $this->net_total,
            'records' => VoucherRecordResource::collection($this->records)
        ];
    }
}
