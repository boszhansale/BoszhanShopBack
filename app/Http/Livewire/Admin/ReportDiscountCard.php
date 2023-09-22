<?php

namespace App\Http\Livewire\Admin;

use App\Models\Counteragent;
use App\Models\Order;
use App\Models\Report;
use App\Models\Store;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportDiscountCard extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $storeId;

    public $search;

    public $start_date;

    public $end_date;

    public function render()
    {
        $reports = Order::query()
            ->where('store_id',$this->storeId)
            ->whereNotNull('discount_phone')
            ->when($this->start_date,function ($q){
                $q->whereDate('created_at','>=',$this->start_date);
            })
            ->when($this->end_date,function ($q){
                $q->whereDate('created_at','<=',$this->end_date);
            })
            ->when($this->search,function ($q){
                $q->where('discount_phone','LIKE','%'.$this->search.'%');
            })
            ->with(['products.product'])
            ->latest()
            ->get();

        return view('admin.report.discount_card_live', [
            'reports' => $reports,
        ]);
    }



    public function mount($storeId)
    {
        $this->storeId =$storeId;
//        $this->start_date = now()->format('Y-m-d');
//        $this->end_date = now()->format('Y-m-d');
    }

}
