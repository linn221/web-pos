<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySalesOverviewResoruce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::createFromFormat('m', $this->month);
        // Get the textual representation of the month
        $textMonth = $date->format('M'); // 'Jun'

        return [
            'date' => collect([$this->day, $textMonth, $this->year])->implode(" "),
            'voucher' => $this->total_voucher,
            'cash' => $this->total_cash,
            'tax' => $this->total_tax,
            'total' => $this->total,
        ];
    }
}
