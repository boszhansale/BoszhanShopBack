<?php

namespace App\Exports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RemainExcelExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public  $remain;

    public function     __construct($remains)
    {
        $this->remains = $remains;
    }

    public function view(): View
    {
        return view('export.admin.remain', [
            'remains' => $this->remains,
        ]);
    }
}
