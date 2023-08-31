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
            'vouchers' => VoucherResource::collection($this),
            'daily_summary' => [
                // @refactor, use local scope
                "total_vouchers" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->count('id'),
                "total_cash" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('total'),
                "total_tax" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('tax'),
                "total" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('net_total')
            ]
        ];
    }
}
