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
use Illuminate\Support\Facades\Gate;

class VoucherController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $vouchers = Voucher::latest("id")
                ->paginate(15)->withQueryString();
        } else {
            // $vouchers = Auth::user()->vouchers()->whereDate('created_at', Carbon::today())->get();
            // $vouchers = Voucher::where("user_id", Auth::id())->whereDate('created_at', Carbon::today())->get();
            $vouchers = Auth::user()->vouchers()->whereDate('created_at', Carbon::today())->get();
        }
        return VoucherResource::collection($vouchers);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            // @fix makes customer_name optional
            'customer_name' => 'nullable',
            'phone_number' => 'nullable',
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
        // @fix, use Product::inWhere to optimize query

        $total = 0;
        foreach ($request->records as $record) {
            $product = Product::find($record['product_id']);
            $quantity = $record['quantity'];
            // @fix, return error/informative message on insufficient stock
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
        $voucher = Auth::user()->vouchers()->find($id);

        if (is_null($voucher)) {
            return response()->json([
                // "success" => false,
                "message" => "Voucher not found",

            ], 404);
        }
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
            return response()->json([
                // "success" => false,
                "message" => "Voucher not found",

            ], 404);
        }

        $voucher->delete();

        return response()->json([
            "message" => "you have deleted voucher"
        ], 204);
    }


    public function restore($id)
    {
        $softDeletedVoucher = Voucher::onlyTrashed()->find($id);
        if (is_null($softDeletedVoucher)) {
            return response()->json([
                'message' => 'Voucher does on exist'
            ]);
        }

        $softDeletedVoucher->restore();

        return response()->json([
            'message' => "Voucher has been restored"
        ], 201);
    }

    public function trash()
    {
        if (Gate::allows('isAdmin')) {
            $trashed_vouchers = Voucher::onlyTrashed()->get();
        } else {
            $trashed_vouchers = Auth::user()->vouchers()->onlyTrashed()->get();
        }

        return VoucherResource::collection($trashed_vouchers);
    }

    public function forceDelete()
    {

    }

    public function emptyTrash()
    {

    }

    public function restoreTrash()
    {

    }
}
