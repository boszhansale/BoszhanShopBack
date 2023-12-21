<?php

namespace App\Exports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReceiptExcelExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public  $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.admin.receipt', [
            'data' => $this->data,
        ]);
    }
}
