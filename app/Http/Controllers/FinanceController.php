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
        // @fix validate date
        Gate::authorize('isAdmin');
        // return $date;

        $carbon = Carbon::createFromFormat('d-m-Y',  $date);
        // return $carbon;
        $vouchers = Voucher::withCount('voucher_records')
            ->whereDate('created_at', $carbon)
            ->paginate(15)->withQueryString();
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
            ->paginate(10)->withQueryString();

        $customSalesOverview = CustomSalesOverviewResoruce::collection($customSalesDateVouchers);
        $customSaleDateSummary = [
            'total_voucher' => Voucher::whereBetween('created_at', [$starteDate, $endDate])->count('id'),
            'total_cash' => Voucher::whereBetween('created_at', [$starteDate, $endDate])->sum('total'),
            'total_tax' => Voucher::whereBetween('created_at', [$starteDate, $endDate])->sum('tax'),
            'total' => Voucher::whereBetween('created_at', [$starteDate, $endDate])->sum('net_total')
        ];


        return response()->json([
            'custom_sale_vouchers' => $customSalesOverview->resource,
            'summary' => $customSaleDateSummary
        ]);
    }



    public function monthly(string $date)
    {
        $carbon = Carbon::createFromFormat('d-m-Y',  $date);
        $numericMonth = $carbon->format('m');
        $year = $carbon->format('Y');

        // Convert month name to numeric month with Carbon
        // $numericMonth = (Carbon::parse($month))->format('m');
        $monthlySaleOverviews = DailySaleOverview::where('month', $numericMonth)->where('year', $year)->latest('id')->paginate(15)->withQueryString();


        $monthlySaleSummary = [
            'total_days' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->count('id'),
            'total_vouchers' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_voucher'),
            'total_cash' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_cash'),
            'total_tax' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total_tax'),
            'total' => DailySaleOverview::where('month', $numericMonth)->where('year', $year)->sum('total'),

        ];

        return response()->json([

            'monthly_sale_overview' => MonthlySalesOverviewResoruce::collection($monthlySaleOverviews)->resource,
            'monthly_sale_summary' => $monthlySaleSummary
        ]);
    }

    public function closeSale(Request $request)
    {

        // @fix, sale close true or false
        $date = new Carbon();
        Gate::authorize('isAdmin');


        // @refactor?
        // repetitive checking of status
        $today = $date->format('d-m-Y');
        $sale_close_status = Setting::key('sale_status')->first();
        if ($sale_close_status->value == $today) {
            // if sale is close
            if ($request->has('false')) {
                $sale_close_status->value = 'false';
                $sale_close_status->save();
                DailySaleOverview::latest()->first()->delete();

                return response()->json([
                    'message' => 'sale is open again'
                ]);

            }

            return response()->json([
                'message' => 'sale is already close'
            ]);
        }

        $sale_close_status->value = $today;
        $sale_close_status->save();

        $dailySaleOverview = DailySaleOverview::create([
            "total_voucher" => Voucher::today()->count('id'),
            "total_cash" => Voucher::today()->sum('total'),
            "total_tax" => Voucher::today()->sum('tax'),
            "total" => Voucher::today()->sum('net_total'),
            "day" => $date->format('d'),
            "month" => $date->format('m'),
            "year" => $date->format('Y')
        ]);

        return response()->json([
            'message' => 'sale close success',
            'overview' => $dailySaleOverview
        ]);
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
