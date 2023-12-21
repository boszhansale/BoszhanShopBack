<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class OrderProductIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $userId;
    public $storeId;
    public $statusId;
    public $users;

    public $counteragentId;
    public $paymentType = 'null';

    public $start_created_at;
    public $end_created_at;

    public function render()
    {
        $query = Order::query()
//            ->join('stores', 'stores.id', 'orders.store_id')
            ->join('order_products','order_products.order_id','orders.id')
            ->join('products','products.id','order_products.product_id')
            ->whereNotNull('check_number')
            ->when($this->search, function ($q) {
                return $q->where('orders.id', 'LIKE', $this->search . '%');
            })
            ->when($this->userId, function ($q) {
                return $q->where('orders.user_id', $this->userId);
            })
            ->when($this->storeId, function ($q) {
                return $q->where('orders.store_id', $this->storeId);
            })
            ->when($this->start_created_at, function ($q) {
                return $q->whereDate('orders.created_at', '>=', $this->start_created_at);
            })
            ->when($this->end_created_at, function ($q) {
                return $q->whereDate('orders.created_at', '<=', $this->end_created_at);
            })

            ->latest()
            ->select(['orders.*','products.name','order_products.price','order_products.count','order_products.all_price']);

        return view('admin.order.product_index_live', [
            'users' => $this->users,

            'orders' => $query->clone()
                ->with(['store'])
                ->paginate(25),
            'query' => $query,
        ]);
    }

    public function mount()
    {
        $this->start_created_at = now()->format('Y-m-d');
        $this->end_created_at = now()->format('Y-m-d');
        $this->users = User::query()
            ->where('users.status', 1)
            ->when($this->storeId, function ( $query) {
                $query->where('store_id',$this->storeId);
            })
            ->whereNotNull('webkassa_login_at')
            ->orderBy('users.name')
            ->get('users.*');
    }
}
