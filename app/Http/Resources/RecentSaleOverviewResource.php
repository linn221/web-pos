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
        if (Auth::user()->role == 'admin') {
            $voucherBuilder = Voucher::query();
        } else {
            $voucherBuilder = Voucher::where('user_id', Auth::id());
        }
        return [
            'vouchers' => VoucherResource::collection($this),
            'daily_summary' => [
                // @refactor, use local scope
                // @refactor, eager loading
                "total_voucher" => $voucherBuilder->whereDate("created_at", Carbon::today())->count('id'),
                "total_cash" => $voucherBuilder->whereDate("created_at", Carbon::today())->sum('total'),
                "total_tax" => $voucherBuilder->whereDate("created_at", Carbon::today())->sum('tax'),
                "total" => $voucherBuilder->whereDate("created_at", Carbon::today())->sum('net_total')
            ]
        ];
    }
}
