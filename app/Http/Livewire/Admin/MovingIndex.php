<?php

namespace App\Http\Livewire\Admin;

use App\Models\Moving;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class MovingIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $userId;
    public $storeId;
    public $statusId;
    public $storageId;

    public $start_created_at;
    public $end_created_at;

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
            'users' => User::query()
                ->where('users.status', 1)
                ->orderBy('users.name')
                ->get('users.*'),

            'movings' => $query->clone()
                ->with(['store'])
                ->paginate(50),
            'query' => $query,
        ]);
    }

    public function mount()
    {

    }
}
