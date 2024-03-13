<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\Receipt;
use App\Models\Refund;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $userId;
    public $storeId;
    public $stores;
    public $statusId;
    public $counteragentId;

    public $start_created_at;
    public $end_created_at;

    public function render()
    {
        $query = Receipt::query()
            ->join('stores', 'stores.id', 'receipts.store_id')
            ->when($this->search, function ($q) {
                return $q->where('receipts.id', 'LIKE', $this->search . '%');
            })

            ->when($this->statusId, function ($q) {
                return $q->where('receipts.status_id', $this->statusId);
            })

            ->when($this->storeId, function ($q) {
                return $q->where('receipts.store_id', $this->storeId);
            })

            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('receipts.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('receipts.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('receipts.*');

        return view('admin.receipt.index_live', [

            'receipts' => $query->clone()
                ->with(['store'])
                ->paginate(50),
            'query' => $query,
        ]);
    }

    public function mount()
    {
        $this->stores = Store::whereNotNull('warehouse_in')->get();
    }
}
