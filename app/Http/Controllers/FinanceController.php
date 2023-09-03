<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailySalesOverviewResource;
use App\Http\Resources\MonthlySalesOverviewResource;
use App\Http\Resources\VoucherResource;
use App\Models\DailySaleOverview;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

class FinanceController extends Controller
{
    //
    public function daily(Request $request, $date)
    {


        Gate::authorize('isAdmin');
        $carbon = Carbon::createFromFormat('Y-m-d',  $date);

        $vouchers = Voucher::withCount('voucher_records')->whereDate('created_at', $date)->paginate(15)->withQueryString();

        $saleOverview = DailySaleOverview
            ::where('day', $carbon->format('d'))
            ->where('month', $carbon->format('m'))
            ->where('year', $carbon->format('Y'))
            ->first();


        $saleOverview->vouchers = $vouchers;


        // return VoucherResource::collection($vouchers);

        return new DailySalesOverviewResource($saleOverview);
    }


    public function customSaleOverview($starteDate, $endDate)
    {
        $customDateVoucher = Voucher::whereBetween('created_at', [$starteDate, $endDate])->get();


        return $customDateVoucher;
    }

    public function monthly($year, $month)
    {


        // Convert month name to numeric month with Carbon
        $numericMonth = (Carbon::parse($month))->format('m');
        $monthlySaleOverviews = DailySaleOverview::where('month', $numericMonth)->where('year', $year)->latest('id')->paginate(10);

        $monthlySaleSummary = [
            'total_days' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->count('id'),
            'total_vouchers' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_voucher'),
            'total_cash' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_cash'),
            'total_tax' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_tax'),
            'total' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total'),

        ];

        // $monthlySaleSummary['monthly_sale_overview'] = $monthlySaleOverviews;
        return response()->json([

            'monthly_sale_overview' => MonthlySalesOverviewResource::collection($monthlySaleOverviews),
            'monthly_sale_summary' => $monthlySaleSummary
        ]);

        // return MonthlySalesOverviewResource::collection($monthlySaleSummary);
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
//
