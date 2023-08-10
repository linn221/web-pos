<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    public function records()
    {
        return $this->hasMany(VoucherRecord::class);
    }

    protected $attributes = [
        'total' => 0,
        'tax' => 0,
        'net-total' => 0
    ];
}
