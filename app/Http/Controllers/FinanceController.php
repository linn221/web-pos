<?php

namespace App\Http\Controllers;

use App\Models\DailySaleOverview;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FinanceController extends Controller
{
    //
    public function daily(Request $request, Carbon $date)
    {
        Gate::authorize('isAdmin');
        

    }

    public function closeSale(Request $request)
    {
        // @fix, sale close true or false
        $date = new Carbon();
        Gate::authorize('isAdmin');

        $dailySaleOverview = DailySaleOverview::create([
                "total_voucher" => Voucher::whereDate("created_at", Carbon::today())->count('id'),
                "total_cash" => Voucher::whereDate("created_at", Carbon::today())->sum('total'),
                "total_tax" => Voucher::whereDate("created_at", Carbon::today())->sum('tax'),
                "total" => Voucher::whereDate("created_at", Carbon::today())->sum('net_total'),
                "day" => $date->format('d'),
                "month" => $date->format('m'),
                "year" => $date->format('Y'),
        ]);
        return $dailySaleOverview;
    }
}
