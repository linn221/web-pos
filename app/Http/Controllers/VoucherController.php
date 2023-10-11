<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailySalesOverviewResource;
use App\Http\Resources\RecentSaleOverviewResource;
use App\Http\Resources\VoucherCollectionResource;
use App\Http\Resources\VoucherDetailResource;
use App\Http\Resources\VoucherResource;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            $voucher_builder = Voucher::query();
        } else {
            $voucher_builder = Auth::user()->vouchers()->today();
        }
        $voucher_builder->withCount('voucher_records');
        if ($request->has('order') && in_array($request->order, ['id', 'created_at', 'voucher_number'])) {
            $voucher_builder->orderBy($request->order, 'desc');
        } else {
            $voucher_builder->orderBy('id', 'desc');
        }

        $vouchers = $voucher_builder->paginate(15)->withQueryString();
        // return new VoucherCollectionResource($vouchers);
        return new RecentSaleOverviewResource($vouchers);
        // return response()->json([
        //     'vouchers' => VoucherResource::collection($vouchers),
        //     'dailyVoucherReprot' => [
        //         "Total Vouchers" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->count('id'),
        //         "Total Tax" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('tax'),
        //         "Total Cash" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('total'),
        //         "Total" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('net_total')
        //     ]
        // ]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Setting::key('sale_status')->first()->value == Carbon::today()->format('d-m-Y')) {
            return response()->json([
                'message' => 'Salse is close. Open it with /finance/close-sale?false if you are admin',
                'request' => $request->all()
            ], 401);
        }
        // return $request;
        $request->validate([
            'customer_name' => 'nullable',
            'phone_number' => 'nullable',
            'voucher_number' => 'required',
            'records' => 'array',
            'records.*.product_id' => 'required|exists:products,id',
            'records.*.quantity' => 'required|numeric|min:1',
            'more_information' => 'nullable'
        ]);

        // creating a voucher
        $voucher = new Voucher;
        $voucher->customer_name = $request->customer_name;
        $voucher->phone_number = $request->phone_number;
        $voucher->voucher_number = $request->voucher_number;
        $voucher->user_id = Auth::id();
        $voucher->more_information = $request->more_information;
        $voucher->save();

        // creating voucher records
        // @fix, use Product::inWhere to optimize query

        $total = 0;
        foreach ($request->records as $record) {
            $product = Product::find($record['product_id']);
            $quantity = $record['quantity'];
            // @fix, return error/informative message on insufficient stock
            // if product have enough stock
            if ($product->total_stock >= $quantity) {
                // create voucher records
                $cost = $product->sale_price * $quantity;
                $voucher->voucher_records()->create([
                    'product_id' => $product->id,
                    'cost' => $cost,
                    'quantity' => $quantity
                ]);

                $total += $cost;
            }
        }

        // update voucher
        $voucher->total = $total;
        $voucher->tax = $total * 0.02;
        $voucher->net_total = $total + $voucher->tax;
        $voucher->save();

        return new VoucherDetailResource($voucher);
        // return $request;
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            abort(404, 'voucher not found');
        }

        $this->authorize('view', $voucher);
        return new VoucherDetailResource($voucher);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            abort(404, 'voucher not found');
        }

        $this->authorize('delete', $voucher);
        $voucher->delete();

        return response()->json([
            "message" => "you have deleted voucher"
        ], 204);
    }


    public function restore($id)
    {
        $voucher = Voucher::onlyTrashed()->find($id);
        if (is_null($voucher)) {
            abort(404, 'voucher does not exist');
        }

        $this->authorize('restore', $voucher);
        $voucher->restore();

        return response()->json([
            'message' => "Voucher has been restored"
        ], 201);
    }

    public function showTrash()
    {
        $trashed_vouchers = Voucher::onlyTrashed()->ownByUser()->withCount('voucher_records')->get();
        return VoucherResource::collection($trashed_vouchers);
    }

    public function forceDelete(string $id)
    {
        Gate::authorize('isAdmin');

        $voucher = Voucher::onlyTrashed()->find($id);
        if (is_null($voucher)) {
            abort(404, 'voucher not found');
        }

        $voucher->forceDelete();
        return response()->json([
            'message' => 'You have deleted voucher permanently'
        ], 204);
    }

    public function emptyBin()
    {
        Gate::authorize('isAdmin');

        Voucher::onlyTrashed()->forceDelete();
        return response()->json([
            'message' => 'Trash has been emptied'
        ], 204);
    }

    public function recycleBin()
    {
        Gate::authorize('isAdmin');

        Voucher::onlyTrashed()->restore();
        return response()->json([
            'message' => 'Trash has been restored'
        ], 201);
    }
}
