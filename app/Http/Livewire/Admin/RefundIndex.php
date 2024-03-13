<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\Refund;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

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
            'refunds' => $query->clone()
                ->with(['store'])
                ->paginate(50),
        ]);
    }

    public function mount()
    {
        $this->stores = Store::whereNotNull('warehouse_in')->get();
    }
}
