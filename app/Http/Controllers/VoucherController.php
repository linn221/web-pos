<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherCollectionResource;
use App\Http\Resources\VoucherResource;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voucher_records = Voucher::all();
        return VoucherCollectionResource::collection($voucher_records);
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

        $voucher = new Voucher;
        $voucher->customer_name = $request->customer_name;
        $voucher->phone_number = $request->phone_number;
        $voucher->user_id = Auth::id();
        $voucher->save();
        $total = 0;
        $voucher_records =[];
        foreach($request->records as $record) {
            $record['voucher_id'] = $voucher->id;
            $cost = $record['quantity'] * Product::find($record['product_id'])->sale_price;
            $voucher_records[] = [
                'product_id' => $record['product_id'],
                'cost' => $cost,
                'voucher_id' => $voucher->id,
                'quantity' => $record['quantity'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            $total += $cost;
        }
        VoucherRecord::insert($voucher_records);
        $voucher->total = $total;
        $voucher->tax = $total * 0.2;
        $voucher->net_total = $total + $voucher->tax;
        $voucher->save();

        return new VoucherResource($voucher);
        // return $request;
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
        //
    }
}
