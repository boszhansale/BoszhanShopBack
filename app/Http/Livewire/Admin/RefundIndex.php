<?php

namespace App\Http\Livewire\Admin;

use App\Models\Refund;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class RefundIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $storeId;
    public $statusId;
    public $stores;
    public $start_created_at;
    public $end_created_at;

    public function mount()
    {
        $this->stores = Store::whereNotNull('warehouse_in')->get();

        // Загружаем фильтры из сессии, если они есть
        $this->search = Session::get('refund_search', '');
        $this->storeId = Session::get('refund_storeId', '');
        $this->start_created_at = Session::get('refund_start_created_at', '');
        $this->end_created_at = Session::get('refund_end_created_at', '');
    }

    public function updated($propertyName)
    {
        // Сохраняем обновленные фильтры в сессию
        Session::put("refund_$propertyName", $this->$propertyName);
    }

    public function render()
    {
        $query = Refund::query()
            ->join('stores', 'stores.id', 'refunds.store_id')
            ->whereNotNull('check_number')
            ->when($this->search, function ($q) {
                return $q->where('refunds.id', 'LIKE', $this->search . '%');
            })
            ->when($this->storeId, function ($q) {
                return $q->where('refunds.store_id', $this->storeId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('refunds.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('refunds.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('refunds.*');

        return view('admin.refund.index_live', [
            'refunds' => $query->clone()->with(['store'])->paginate(50),
        ]);
    }
}
