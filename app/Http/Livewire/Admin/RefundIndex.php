<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\Refund;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class RefundIndex extends Component
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
        $query = Refund::query()
            ->join('stores', 'stores.id', 'refunds.store_id')
            ->when($this->search, function ($q) {
                return $q->where('refunds.id', 'LIKE', $this->search . '%');
            })

            ->when($this->statusId, function ($q) {
                return $q->where('refunds.status_id', $this->statusId);
            })
            ->when($this->userId, function ($q) {
                return $q->where('refunds.user_id', $this->userId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('refunds.store_id', $this->storeId);
            })
            ->when($this->counteragentId, function ($q) {
                return $q->where('stores.counteragent_id', $this->counteragentId);
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
            'users' => User::query()
                ->where('users.status', 1)
                ->orderBy('users.name')
                ->get('users.*'),

            'refunds' => $query->clone()
                ->with(['store'])
                ->paginate(50),
            'query' => $query,
        ]);
    }

    public function mount()
    {

    }
}
