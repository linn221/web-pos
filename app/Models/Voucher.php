<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $attributes = [
        'total' => 0,
        'tax' => 0,
        'net_total' => 0,
        'voucher_number' => 2000000
    ];

    public function voucher_records()
    {
        return $this->hasMany(VoucherRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($builder)
    {
        return $builder->whereDate('vouchers.created_at', Carbon::today());
    }

    public function scopeThatDay($builder, string $date)
    {
        $carbon = Carbon::createFromFormat('d-m-Y', $date);

        return $builder->whereDate('vouchers.created_at', $carbon);

    }

    public function scopeThisWeek($builder)
    {
        $now = Carbon::now();
        $end_date = $now->format('Y-m-d');
        $start_date = $now->startOfWeek()->format('Y-m-d');
        return $builder->whereBetween('vouchers.created_at', [$start_date, $end_date]);
    }

    public function scopeThisMonth($builder)
    {
        $now = Carbon::now();
        $end_date = $now->format('Y-m-d');
        $start_date = $now->startOfMonth()->format('Y-m-d');
        return $builder->whereBetween('vouchers.created_at', [$start_date, $end_date]);
    }

    public function scopeDateBetween($builder, string $from_str, string $to_str)
    {
        return $builder->whereBetween('vouchers.created_at', [$from_str, $to_str]);
    }

    public function scopeOwnByUser($builder)
    {
        if (Auth::user()->role == 'admin') {
            return $builder;
        }
        return $builder->where('user_id', Auth::user()->id);
    }
}
