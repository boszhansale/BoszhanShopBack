<?php

namespace App\Http\Livewire\Admin;

use App\Models\Inventory;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class InventoryIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $userId;
    public $storeId;
    public $statusId;
    public $counteragentId;
    public $stores;
    public $start_created_at;
    public $end_created_at;

    public function mount()
    {
        // Загружаем фильтры из сессии
        $this->search = Session::get('inventory_search', '');
        $this->userId = Session::get('inventory_userId', '');
        $this->storeId = Session::get('inventory_storeId', '');
        $this->statusId = Session::get('inventory_statusId', '');
        $this->counteragentId = Session::get('inventory_counteragentId', '');
        $this->start_created_at = Session::get('inventory_start_created_at', '');
        $this->end_created_at = Session::get('inventory_end_created_at', '');

        $this->stores = Store::whereNotNull('warehouse_in')->get();
    }

    public function updated($propertyName)
    {
        // Сохраняем обновленные фильтры в сессию
        Session::put("inventory_$propertyName", $this->$propertyName);
    }

    public function render()
    {
        $query = Inventory::query()
            ->join('stores', 'stores.id', 'inventories.store_id')
            ->when($this->search, function ($q) {
                return $q->where('inventories.id', 'LIKE', $this->search . '%');
            })
            ->when($this->statusId, function ($q) {
                return $q->where('inventories.status_id', $this->statusId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('inventories.store_id', $this->storeId);
            })
            ->when($this->counteragentId, function ($q) {
                return $q->where('stores.counteragent_id', $this->counteragentId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('inventories.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('inventories.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('inventories.*');

        return view('admin.inventory.index_live', [
            'inventories' => $query->clone()
                ->with(['store'])
                ->paginate(50),
            'query' => $query,
        ]);
    }
}
