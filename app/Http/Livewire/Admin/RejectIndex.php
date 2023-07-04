<?php

namespace App\Http\Livewire\Admin;

use App\Models\Reject;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class RejectIndex extends Component
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
        $query = Reject::query()
            ->join('stores', 'stores.id', 'rejects.store_id')
            ->when($this->search, function ($q) {
                return $q->where('rejects.id', 'LIKE', $this->search . '%');
            })

            ->when($this->statusId, function ($q) {
                return $q->where('rejects.status_id', $this->statusId);
            })
            ->when($this->userId, function ($q) {
                return $q->where('rejects.user_id', $this->userId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('rejects.store_id', $this->storeId);
            })
            ->when($this->counteragentId, function ($q) {
                return $q->where('stores.counteragent_id', $this->counteragentId);
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
            'users' => User::query()
                ->where('users.status', 1)
                ->orderBy('users.name')
                ->get('users.*'),

            'rejects' => $query->clone()
                ->with(['store'])
                ->paginate(50),
            'query' => $query,
        ]);
    }

    public function mount()
    {

    }
}
