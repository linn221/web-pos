<?php

namespace App\Http\Resources;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class FinanceDailyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            // 'vouchers' => FinanceDailyVoucherResource::collection($this->vouchers),
            // 'vouchers' => FinanceDailyVoucherResource::collection(Voucher::whereDate('created_at', $request->date)->paginate(5)),
            'total_voucher' => $this->total_voucher,
            'total_cash ' => $this->total_cash,
            'total_tax' => $this->total_tax,
            'total' => $this->total

        ];
    }
}
