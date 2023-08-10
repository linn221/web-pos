<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $voucher->save();

        $total = 0;
        $voucher_records = $request->records;
        foreach($voucher_records as $record) {
            $record['voucher_id'] = $voucher->id;
            $record['cost'] = Product::find($record->product_id)->sale_price;
            $total += $record['cost'];
            // $cost = ;

        }
        VoucherRecord::create($voucher_records);
        $tax = $total * 0.2;
        $net_total = $total + $tax;

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
