<?php

namespace App\Exports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderProductExcelExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public  $orders;
    public  $count;
    public  $totalPrice;
    public  $start;
    public  $end;

    public function     __construct($orders,$count,$totalPrice,$start,$end)
    {
        $this->orders = $orders;
        $this->count = $count;
        $this->totalPrice = $totalPrice;
        $this->start = $start;
        $this->end = $end;

    }

    public function view(): View
    {
        return view('export.admin.order_product', [
            'orders' => $this->orders,
            'count' => $this->count,
            'totalPrice' => $this->totalPrice,
            'start' => $this->start,
            'end' => $this->end
        ]);
    }
}
