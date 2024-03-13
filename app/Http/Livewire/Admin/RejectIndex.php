<?php

namespace App\Http\Livewire\Admin;

use App\Models\Reject;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class RejectIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $storeId;
    public $stores;
    public $statusId;
    public $counteragentId;

    public $start_created_at;
    public $end_created_at;

    public function render()
    {
        $query = Reject::query()
            ->join('stores', 'stores.id', 'rejects.store_id')
            ->when($this->search, function ($q) {
                return $q->where('rejects.id', 'LIKE', $this->search . '%');
            })
            ->when($this->storeId, function ($q) {
                return $q->where('rejects.store_id', $this->storeId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('rejects.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('rejects.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('rejects.*');

        return view('admin.reject.index_live', [
            'rejects' => $query->clone()
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
