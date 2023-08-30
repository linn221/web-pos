<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherDetailResource;
use App\Http\Resources\VoucherResource;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $totalTax = Voucher::all()->pluck('tax')->sum();

        if (Auth::user()->role == "admin") {
            $vouchers = Voucher::latest("id")
                ->paginate(7)->withQueryString();
        } else {
            // $vouchers = Auth::user()->vouchers()->whereDate('created_at', Carbon::today())->get();
            $vouchers = Voucher::where("user_id", Auth::id())->whereDate('created_at', Carbon::today())->paginate(7)->withQueryString();
        }
        // return VoucherResource::collection($vouchers);
        return response()->json([
            'vouchers' => VoucherResource::collection($vouchers),
            'dailyVoucherReprot' => [
                "Total Vouchers" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->count('id'),
                "Total Cash" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('total'),
                "Total Tax" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('tax'),
                "Total" => Voucher::where('user_id', Auth::id())->whereDate("created_at", Carbon::today())->sum('net_total')
            ]
        ]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        // return $request;
        $request->validate([
            'customer_name' => 'required|min:5|max:20',
            'phone_number' => 'required',
            'records' => 'array',
            'records.*.product_id' => 'required|exists:products,id',
            'records.*.quantity' => 'required|numeric|min:1'
        ]);

        // creating a voucher
        $voucher = new Voucher;
        $voucher->customer_name = $request->customer_name;
        $voucher->phone_number = $request->phone_number;
        $voucher->user_id = Auth::id();
        $voucher->save();

        // creating voucher records
        $total = 0;
        foreach ($request->records as $record) {
            $product = Product::find($record['product_id']);
            $quantity = $record['quantity'];
            if ($product->total_stock >= $quantity) {
                $cost = $product->sale_price * $quantity;
                VoucherRecord::create([
                    'product_id' => $product->id,
                    'cost' => $cost,
                    'voucher_id' => $voucher->id,
                    'quantity' => $quantity
                ]);
                $total += $cost;
            }
        }

        // update voucher
        $voucher->total = $total;
        $voucher->tax = $total * 0.2;
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
            return response()->json([
                // "success" => false,
                "message" => "Product not found",

            ], 404);
        }
        return new VoucherDetailResource($voucher);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            return response()->json([
                // "success" => false,
                "message" => "Voucher not found",

            ], 404);
        }

        $voucher->delete();

        return response()->json([
            "message" => "you have deleted voucher"
        ]);
    }


    public function restore($id)
    {
        $softDeletedVoucher = Voucher::withTrashed()->find($id);
        if (is_null($softDeletedVoucher)) {
            return response()->json([
                'message' => 'SoftdeletedVoucher is not found'
            ]);
        }

        $softDeletedVoucher->restore();

        return response()->json([
            'message' => "softdeletedVoucher has been restored"
        ]);
    }
}
