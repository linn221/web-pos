<?php

namespace App\Models;

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


    }

    public function scopeUser($builder)
    {

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
