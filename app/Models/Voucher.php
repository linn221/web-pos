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
    public function voucher_records()
    {
        return $this->hasMany(VoucherRecord::class);
    }

    protected $attributes = [
        'total' => 0,
        'tax' => 0,
        'net_total' => 0,
        'voucher_number' => 2000000
    ];

    public function scopeToday($builder)
    {
        return $builder->whereDate('created_at', Carbon::today());

    }

    public function scopeThatDay($builder, string $date)
    {
        $carbon = Carbon::createFromFormat('d-m-Y', $date);

        return $builder->whereDate('created_at', $carbon);

    }

    public function scopeOwnByUser($builder)
    {
        if (Auth::user()->role == 'admin') {
            return $builder;
        }
        return $builder->where('user_id', Auth::user()->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
