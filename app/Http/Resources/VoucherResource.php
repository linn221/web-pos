<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'cash' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'record_count' => $this->voucher_records->count(),
            'voucher_number' => $this->voucher_number,
            'more_information' => $this->more_information,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'voucher_records' => VoucherRecordResource::collection($this->voucher_records)
        ];
    }
}
