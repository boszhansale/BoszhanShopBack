<?php

namespace App\Http\Livewire\Admin;

use App\Models\Moving;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class MovingIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $userId;
    public $storeId;
    public $statusId;
    public $storageId;
    public $stores;
    public $users;
    public $operation;

    public $start_created_at;
    public $end_created_at;

    public function mount()
    {
        // Загружаем фильтры из сессии
        $this->search = Session::get('moving_search', '');
        $this->userId = Session::get('moving_userId', '');
        $this->storeId = Session::get('moving_storeId', '');
        $this->statusId = Session::get('moving_statusId', '');
        $this->storageId = Session::get('moving_storageId', '');
        $this->operation = Session::get('moving_operation', '');
        $this->start_created_at = Session::get('moving_start_created_at', '');
        $this->end_created_at = Session::get('moving_end_created_at', '');

        $this->stores = Store::whereNotNull('warehouse_in')->get();
        $this->users = User::query()
            ->where('users.status', 1)
            ->orderBy('users.name')
            ->get('users.*');
    }

    public function updated($propertyName)
    {
        // Сохраняем обновленные фильтры в сессию
        Session::put("moving_$propertyName", $this->$propertyName);
    }

    public function render()
    {
        $query = Moving::query()
            ->join('stores', 'stores.id', 'movings.store_id')
            ->when($this->search, function ($q) {
                return $q->where('movings.id', 'LIKE', $this->search . '%');
            })
            ->when($this->statusId, function ($q) {
                return $q->where('movings.status_id', $this->statusId);
            })
            ->when($this->operation, function ($q) {
                return $q->where('movings.operation', $this->operation);
            })
            ->when($this->userId, function ($q) {
                return $q->where('movings.user_id', $this->userId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('movings.store_id', $this->storeId);
            })
            ->when($this->storageId, function ($q) {
                return $q->where('movings.storage_id', $this->storageId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('movings.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('movings.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('movings.*');

        return view('admin.moving.index_live', [
            'movings' => $query->clone()
                ->with(['store'])
                ->paginate(50),
        ]);
    }
}
