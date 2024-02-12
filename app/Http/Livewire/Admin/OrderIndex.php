<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Query\Builder;
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

    public $discountPhoneBool;
    public $discountBool;
    public $onlineBool;
    public $users;

    public $counteragentId;
    public $paymentType = 'null';

    public $start_created_at;
    public $end_created_at;
    public $discountPhone;

    public function render()
    {
        $query = Order::query()
            ->join('stores', 'stores.id', 'orders.store_id')
            ->whereNotNull('check_number')
            ->when($this->search, function ($q) {
                return $q->where('orders.id', 'LIKE', $this->search . '%');
            })

            ->when($this->statusId, function ($q) {
                return $q->where('orders.status_id', $this->statusId);
            })
            ->when($this->discountPhone, function ($q) {
                return $q->where('orders.discount_phone', $this->discountPhone);
            })
            ->when($this->paymentType != 'null', function ($q) {
                return $q->whereJsonContains('payments', ['PaymentType' => (int)$this->paymentType]);
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
            ->when($this->discountBool == 1, function ($q) {
                return $q->where('orders.total_discount_price', '>', 0);
            })
            ->when($this->discountPhoneBool == 1, function ($q) {
                return $q->whereNotNull('orders.discount_phone');
            })
            ->when($this->onlineBool == 1, function ($q) {
                return $q->where('orders.online_sale', 1);
            })
            ->latest()
            ->select('orders.*');

        return view('admin.order.index_live', [
            'users' => $this->users,

            'orders' => $query->clone()
                ->with(['store'])
                ->withTrashed()
                ->paginate(25),

            'query' => $query,
        ]);
    }

    public function mount()
    {
        $this->users =  User::query()
            ->where('users.status', 1)
            ->when($this->storeId, function ( $query) {
                $query->where('store_id',$this->storeId);
            })
            ->whereNotNull('webkassa_login_at')
            ->orderBy('users.name')
            ->get('users.*');
    }
}
