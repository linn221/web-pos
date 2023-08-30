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
            'records' => VoucherRecordResource::collection($this->voucher_records),
            'record_count' => $this->voucher_records->count(),
            'user_id' => $this->user_id,
            'more_information' => $this->more_information,
            'create_date' => $this->created_at->format('d-m-y'),
            'update_date' => $this->updated_at->format('d-m-y'),
        ];
    }
}
