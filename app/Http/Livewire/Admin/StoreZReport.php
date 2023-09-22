<?php

namespace App\Http\Livewire\Admin;

use App\Models\Counteragent;
use App\Models\Report;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class StoreZReport extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $storeId;

    public $start_date;

    public $end_date;

    public function render()
    {
        $reports = Report::query()
            ->where('name','z-report')
            ->where('store_id',$this->storeId)
            ->when($this->start_date, function ($query) {
                return $query->whereDate('reports.created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                return $query->whereDate('reports.created_at', '<=', $this->end_date);
            })
            ->orderBy('reports.id', 'desc')
            ->paginate(50);

        return view('admin.store.z-report_live', [
            'reports' => $reports,
        ]);
    }



    public function mount($storeId)
    {
        $this->storeId = $storeId;
    }

}
