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

    public function     __construct($orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('export.admin.order_product', [
            'orders' => $this->orders,
        ]);
    }
}
