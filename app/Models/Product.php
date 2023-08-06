<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand_id', 'user_id', 'actual_price', "sale_price", "total_stock", "unit", "more_information", "photo"];
}
