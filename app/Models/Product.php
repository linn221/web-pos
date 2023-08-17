<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand_id', 'actual_price', "sale_price", "unit", "more_information", "photo"];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
