<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySaleOverview extends Model
{
    use HasFactory;

    protected $fillable = ['total_voucher', 'total_cash', 'total_tax', 'total', 'month'];
}
