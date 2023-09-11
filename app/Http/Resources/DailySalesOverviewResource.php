<?php

namespace App\Http\Resources;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class DailySalesOverviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vouchers' => VoucherResource::collection($this->vouchers)->resource,
            'daily_summary' => [
                // 'x' => $this->x,
                'total_voucher' => $this->total_voucher,
                'total_tax' => $this->total_tax,
                'total_cash' => $this->total_cash,
                'total' => $this->total,
            ]
        ];
    }
}
