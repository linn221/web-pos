<?php

namespace App\Http\Controllers;

use App\Models\DailySaleOverview;
use App\Models\Voucher;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PDO;

class SaleReportController extends Controller
{

    public function dailySaleAnalysis()
    {
    }

    public function summaryWeek(Request $request)
    {
        $now = Carbon::now();
        $current_month = (int) $now->format('m');
        $current_year = (int) $now->format('Y');
        $current_day = (int) $now->format('d');

        $start_day = (int) $now->startOfWeek()->format('d');
        $daily_overview = DailySaleOverview::query()
            ->where('year', $current_year)
            ->where('month', $current_month)
            ->whereBetween('day', [$start_day, $current_day])
            ->get();
        
        // return $daily_overview[0]->only(['id', 'total']);

        $daily_overview_r = $daily_overview->map(function(DailySaleOverview $value, $key){
            return $value->only(['id', 'total', 'day', 'date']);
        });

        // $day_total_array = $daily_overview->only(['day', 'total']);
        $stats = $this->getStats($daily_overview);

        return response()->json([
            'daily_total' => $daily_overview_r,
            'stats' => $stats
        ]);
    }

    private function getStats(Collection $sale_overview) : array
    {
        $highest = $sale_overview->sortByDesc('total')->first();
        $average = $sale_overview->avg('total');
        $lowest = $sale_overview->sortBy('total')->first();

        return compact('highest', 'lowest', 'average');
    }

    private function getWeeklyOverview(Collection $sale_overview, int $end_day=31) : Collection
    {
        $week = 1;
        $day = 1;
        $weekly_overview = [];
        while ($day <= $end_day) {
            $weekly_overview[] = [
                'week' => $week,
                'total' => $sale_overview->whereBetween('day', [$day, $day+6])->sum('total')
            ];
            $week += 1;
            $day += 7;
        }
        return collect($weekly_overview);
    }

    public function summaryMonth(Request $request)
    {
        // return $request;
        $now = Carbon::now();
        $current_year = (int) $now->format('Y');
        $current_month = (int) $now->format('m');
        $end_day = (int) $now->format('d');

        if ($request->has('month') && is_numeric($request->month)) {
            $current_month = $request->month;
            $end_day = 31;
        }

        $sales_this_month = DailySaleOverview::query()
            ->where('year', $current_year)
            ->where('month', $current_month)
            ->get();

        // get weekly total array
        $weekly_overview = $this->getWeeklyOverview($sales_this_month, $end_day);
        $stats = $this->getStats($weekly_overview);
        return response()->json([
            'weekly_total' => $weekly_overview,
            'stats' => $stats
        ]);

        // return response()->json([
        //     'weekly_total' => $weekly_total,

        // ]);
        // return response()->json(compact(['sales_this_month', 'weekly_total', 'current_day', 'current_month']));
    }

    // public function statisticsThatDay(Request $request)
    // {
    //     $this_day = Carbon::createFromFormat('d-m-Y', $request->date);
    //     // compare with yesterday BY DEFAULT
    //     $that_day = $this_day->subDay();
    //     if ($request->has('compare')) {
    //         if ($request->compare == 'lastWeek') {
    //             $that_day = $this_day->subWeek();
    //         }
    //     }
    // }

    public function weeklyBrief()
    {
    }

    public function bestSaleProducts()
    {
        $vouchers = Voucher::today()->with('voucher_records')->get();
        $voucher_records = $vouchers->map(function (Voucher $voucher, int $key) {
            return $voucher->voucher_records;
        });
        return $voucher_records;
    }

    public function bestSaleBrands()
    {
    }
    //
}
