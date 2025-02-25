<?php

namespace App\Http\Livewire\Admin;

use App\Models\RefundProducer;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class RefundProducerIndex extends Component
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

    public function mount()
    {
        // Загружаем значения фильтров из сессии
        $this->search = Session::get('refund_producer_search', '');
        $this->userId = Session::get('refund_producer_userId', '');
        $this->storeId = Session::get('refund_producer_storeId', '');
        $this->statusId = Session::get('refund_producer_statusId', '');
        $this->counteragentId = Session::get('refund_producer_counteragentId', '');
        $this->start_created_at = Session::get('refund_producer_start_created_at', '');
        $this->end_created_at = Session::get('refund_producer_end_created_at', '');
    }

    public function updated($propertyName)
    {
        // Сохраняем обновленные фильтры в сессию
        Session::put("refund_producer_$propertyName", $this->$propertyName);
    }

    public function render()
    {
        $query = RefundProducer::query()
            ->join('stores', 'stores.id', 'refund_producers.store_id')
            ->when($this->search, function ($q) {
                return $q->where('refund_producers.id', 'LIKE', $this->search . '%');
            })
            ->when($this->statusId, function ($q) {
                return $q->where('refund_producers.status_id', $this->statusId);
            })
            ->when($this->userId, function ($q) {
                return $q->where('refund_producers.user_id', $this->userId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('refund_producers.store_id', $this->storeId);
            })
            ->when($this->counteragentId, function ($q) {
                return $q->where('stores.counteragent_id', $this->counteragentId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('refund_producers.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('refund_producers.created_at', '<=', $this->end_created_at);
            })
            ->latest()
            ->select('refund_producers.*');

        return view('admin.refundProducer.index_live', [
            'users' => User::query()
                ->where('users.status', 1)
                ->orderBy('users.name')
                ->get('users.*'),

            'refundProducers' => $query->clone()
                ->with(['store'])
                ->paginate(50),
            'query' => $query,
        ]);
    }
}
