<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\Store;
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

    public $paymentType = 'null';

    public $start_created_at;
    public $end_created_at;
    public $stores;

    public function render()
    {
        $orders = Order::query()
//            ->join('stores', 'stores.id', 'orders.store_id')
            ->join('order_products','order_products.order_id','orders.id')
            ->join('products','products.id','order_products.product_id')
            ->whereNotNull('orders.check_number')
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

            ->selectRaw('store_id,product_id,products.name,price,SUM(count) as count,SUM(all_price) as all_price,orders.user_id')
            ->groupBy('store_id','product_id','price','orders.user_id')
            ->orderBy('products.name')
            ->orderBy('store_id')
            ->get();

        return view('admin.order.product_index_live', [
            'users' => $this->users,
            'orders' => $orders
        ]);
    }

    public function mount()
    {
        $this->start_created_at = now()->subDay()->format('Y-m-d');
        $this->end_created_at = now()->format('Y-m-d');
        $this->users = User::query()
            ->where('users.status', 1)
            ->when($this->storeId, function ( $query) {
                $query->where('store_id',$this->storeId);
            })
            ->whereNotNull('webkassa_login_at')
            ->orderBy('users.name')
            ->get('users.*');
        $this->stores = Store::query()
            ->orderBy('stores.name')
            ->get();
    }
}
