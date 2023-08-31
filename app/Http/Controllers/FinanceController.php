<?php

namespace App\Http\Controllers;

use App\Http\Resources\FinanceDailyResource;
use App\Http\Resources\FinanceDailyVoucherResource;
use App\Models\DailySaleOverview;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class FinanceController extends Controller
{
    public function saleClose()
    {

        // return 'hello';
        $dailySaleOverview = DailySaleOverview::create([
            'total_voucher' => Voucher::whereDate("created_at", Carbon::today())->count('id'),
            'total_cash' => Voucher::whereDate("created_at", Carbon::today())->sum('total'),
            'total_tax' => Voucher::whereDate("created_at", Carbon::today())->sum('tax'),
            'total' => Voucher::whereDate("created_at", Carbon::today())->sum('net_total'),
            'month' => Carbon::now()->format('m')
        ]);

        return response()->json($dailySaleOverview);
    }



    public function daily(Request $request)
    {



        $dailyVouchers = Voucher::whereDate('created_at', $request->date)->latest('id')->paginate(5);

        $dailySaleReport = DailySaleOverview::whereDate('created_at', $request->date)->get();
        // $dailySaleReport->vouchers = $dailyVouchers;




        return  response()->json([
            'dailyVoucher' => FinanceDailyVoucherResource::collection($dailyVouchers),
            'dailySaleReport' => $dailySaleReport
        ]);
    }
}
