<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $userId;
    public $storeId;
    public $statusId;
    public $counteragentId;

    public $start_created_at;
    public $end_created_at;

    public function render()
    {
        $query = Order::query()
            ->join('stores', 'stores.id', 'orders.store_id')
            ->when($this->search, function ($q) {
                return $q->where('orders.id', 'LIKE', $this->search . '%');
            })

            ->when($this->statusId, function ($q) {
                return $q->where('orders.status_id', $this->statusId);
            })
            ->when($this->userId, function ($q) {
                return $q->where('orders.user_id', $this->userId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('orders.store_id', $this->storeId);
            })
            ->when($this->counteragentId, function ($q) {
                return $q->where('stores.counteragent_id', $this->counteragentId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('orders.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('orders.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('orders.*');

        return view('admin.order.index_live', [
            'users' => User::query()
                ->where('users.status', 1)
                ->orderBy('users.name')
                ->get('users.*'),

            'orders' => $query->clone()
                ->with(['store'])
                ->withTrashed()
                ->paginate(50),
            'query' => $query,
        ]);
    }

    public function mount()
    {

    }
}
