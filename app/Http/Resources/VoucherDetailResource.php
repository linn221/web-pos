<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherDetailResource extends JsonResource
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
            // @fix
            // 'customer_name' => $this->customer_name,
            // 'phone_number' => $this->phone_number,
            'cash' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'records' => VoucherRecordResource::collection($this->voucher_records),
        ];
    }
}
