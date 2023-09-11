<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;


    protected $fillable = [
        "name",
        "company",
        "photo",
        "agent",
        "phone_no",
        "description"
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function stocks()
    {
        return $this->hasManyThrough(Stock::class, Product::class);
    }
}
