<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomSalesOverviewResoruce;
use App\Http\Resources\DailySalesOverviewResource;
use App\Http\Resources\MonthlySalesOverviewResoruce;
use App\Http\Resources\VoucherResource;
use App\Models\DailySaleOverview;
use App\Models\Setting;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use PhpParser\Node\Stmt\Foreach_;

class FinanceController extends Controller
{
    //
    public function daily(Request $request, $date)
    {
        Gate::authorize('isAdmin');
        // return $date;
        $carbon = Carbon::createFromFormat('Y-m-d',  $date);
        // return $carbon;
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

        $customSalesDateVouchers = Voucher::query()
            ->whereBetween('created_at', [$starteDate, $endDate])
            ->get();
        $customSaleDateSummary = [
            'total_voucher' => $customSalesDateVouchers->pluck('id')->count(),
            'total_cash' => $customSalesDateVouchers->pluck('total')->sum(),
            'total_tax' => $customSalesDateVouchers->pluck('tax')->sum(),
            'total' => $customSalesDateVouchers->pluck('net_total')->sum()
        ];


        return response()->json([
            'custom_sale_overview_vouchers' =>  CustomSalesOverviewResoruce::collection($customSalesDateVouchers),
            'summary' => $customSaleDateSummary
        ]);
    }



    public function monthly($year, $month)
    {

        // Convert month name to numeric month with Carbon
        $numericMonth = (Carbon::parse($month))->format('m');
        $monthlySaleOverviews = DailySaleOverview::where('month', $numericMonth)->where('year', $year)->latest('id')->paginate(10)->withQueryString();

        $monthlySaleSummary = [
            'total_days' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->count('id'),
            'total_vouchers' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_voucher'),
            'total_cash' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_cash'),
            'total_tax' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_tax'),
            'total' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total'),

        ];

        return response()->json([

            'monthly_sale_overview' => MonthlySalesOverviewResoruce::collection($monthlySaleOverviews),
            'monthly_sale_summary' => $monthlySaleSummary
        ]);
    }

    public function closeSale(Request $request)
    {

        // @fix, sale close true or false
        $date = new Carbon();
        Gate::authorize('isAdmin');


        // @refactor?
        // update setting
        $today = $date->format('d-m-Y');
        $sale_close_status = Setting::key('sale_status')->first();
        if ($sale_close_status->value == $today) {
            if (!$request->has('force')) {
                return response()->json([
                    'message' => 'sale is already close for today. force this action by ?force query suffix'
                ]);
            }
        } else {
            $sale_close_status->value = $today;
            $sale_close_status->save();
        }

        // @security Update DailySaleOverview instead of creating a new one

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

    public function checkSaleClose()
    {
        $today = Carbon::today()->format('d-m-Y');
        if (Setting::key('sale_status')->first()->value == $today) {
            return response()->json([
                'status' => 'close'
            ]);
        } else {
            return response()->json([
                'status' => 'open'
            ]);
        }
    }
}
//
