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
        $voucher = Voucher::find($id);
        if (is_null($voucher)) {
            return response()->json([
                // "success" => false,
                "message" => "Voucher not found",

            ], 404);
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
            return response()->json([
                // "success" => false,
                "message" => "Voucher not found",

            ], 404);
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
            return response()->json([
                'message' => 'Voucher does on exist'
            ]);
        }

        $this->authorize('restore', $voucher);
        $voucher->restore();

        return response()->json([
            'message' => "Voucher has been restored"
        ], 201);
    }

    public function showTrash()
    {
        if (Gate::allows('isAdmin')) {
            $trashed_vouchers = Voucher::onlyTrashed()->get();
        } else {
            $trashed_vouchers = Auth::user()->vouchers()->onlyTrashed()->get();
        }

        return VoucherResource::collection($trashed_vouchers);
    }

    // isAdmin middleware
    public function forceDelete(string $id)
    {
        $voucher = Voucher::onlyTrashed()->find($id);
        if (is_null($voucher)) {
            return response()->json([
                'message' => 'Voucher does on exist'
            ]);
        }

        // @fix
        $voucher->forceDelete();
        return response()->json([
            'message' => 'You have deleted voucher permanently'
        ], 204);
    }

    // isAdmin middleware
    public function emptyBin()
    {
        Voucher::onlyTrashed()->forceDelete();
        return response()->json([
            'message' => 'Trash has been emptied'
        ], 204);

    }

    // isAdmin middleware
    public function recycleBin()
    {
        Voucher::onlyTrashed()->restore();

        return response()->json([
            'message' => 'Trash has been restored'
        ], 201);
    }
}
