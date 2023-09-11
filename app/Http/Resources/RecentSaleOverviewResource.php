<?php

namespace App\Http\Resources;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class RecentSaleOverviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $voucherBuilder = Voucher::ownByUser();
        return [
            'vouchers' => VoucherResource::collection($this),
            'daily_summary' => [
                // @refactor, use local scope
                // @refactor, eager loading
                "total_voucher" => $voucherBuilder->today()->count('id'),
                "total_cash" => $voucherBuilder->today()->sum('total'),
                "total_tax" => $voucherBuilder->today()->sum('tax'),
                "total" => $voucherBuilder->today()->sum('net_total')
            ]
        ];
    }
}
