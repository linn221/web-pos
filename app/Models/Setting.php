<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public function scopeKey($builder, string $key)
    {
        return $builder->where('key', $key);
    }
}
