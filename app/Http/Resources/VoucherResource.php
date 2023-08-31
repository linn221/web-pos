<?php

namespace App\Http\Resources;

use App\Models\Voucher;
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
            'voucher_number' => $this->voucher_number,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'total' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'product_count' => $this->voucher_records->sum('voucher_id'),
            'created_at' => $this->created_at

            // 'voucher_records' => VoucherRecordResource::collection($this->voucher_records)
        ];
    }
}
