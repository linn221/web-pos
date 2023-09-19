<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySaleOverview extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function getDateAttribute()
    {
        return $this->day . '-' . $this->month . '-' . $this->year;
    }

    protected $appends = ['date'];
}
