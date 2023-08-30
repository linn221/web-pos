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
            'record_count' => $this->voucher_records_count,
            'voucher_number' => $this->voucher_number,
            'user_id' => $this->user_id,
            'more_information' => $this->more_information,
            'create_date' => $this->created_at->format('d-m-y'),
            'update_date' => $this->updated_at->format('d-m-y'),
            // 'voucher_records' => VoucherRecordResource::collection($this->voucher_records)
        ];
    }
}
