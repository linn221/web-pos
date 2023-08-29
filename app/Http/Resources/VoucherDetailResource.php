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
            'voucher_number' => $this->voucher_number,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'cash' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'record_count' => $this->voucher_records->count(),
            'records' => VoucherRecordResource::collection($this->voucher_records),
            'more_information' => $this->more_information,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
