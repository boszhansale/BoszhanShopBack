<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Moving;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ProductInfo extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public $brand_id;
    public $category_id;
    public $product_id;
    public $store_id;
    public $user_id;

    public $brands = [];
    public $categories = [];
    public $products = [];
    public $stores = [];
    public $users = [];

    public $orders = [];
    public $movingFrom = [];
    public $movingTo = [];
    public $orderInfo;

    public $inventories = [];


    public function mount()
    {
        $this->brands = Brand::orderBy('name')
            ->where('enabled', 1)
            ->get();
        $this->stores = Store::orderBy('name')
            ->whereNotNull('warehouse_in')
            ->get();
        $this->users = User::orderBy('name')
            ->whereNotNull('webkassa_cash_box_id')
            ->get();
    }
    public function render()
    {
        $this->categories = Category::orderBy('name')
            ->where('brand_id', $this->brand_id)
            ->get();
       if ($this->category_id){
           $this->products = Product::query()
               ->where('category_id', $this->category_id)
               ->orderBy('products.name')
               ->get();
       }
       if ($this->product_id){
           $this->orders = Order::query()
               ->join('order_products', 'orders.id', '=', 'order_products.order_id')
               ->join('stores', 'stores.id', '=', 'orders.store_id')
               ->join('users', 'users.id', '=', 'orders.user_id')
               ->where('order_products.product_id', $this->product_id)
               ->when( $this->store_id,function ($query){
                    $query->where('stores.id', $this->store_id);
                })
               ->when($this->user_id,function ($query){
                   $query->where('users.id', $this->user_id);
               })
               ->whereNotNull('orders.check_number')
               ->selectRaw('orders.id,stores.name as store_name,users.name as user_name,order_products.price,order_products.count,order_products.all_price,discount_price,orders.created_at')
               ->orderBy('orders.id', 'desc')
               ->get();
           $this->orderInfo = Order::query()
               ->join('order_products', 'orders.id', '=', 'order_products.order_id')
               ->join('stores', 'stores.id', '=', 'orders.store_id')
               ->join('users', 'users.id', '=', 'orders.user_id')
               ->where('order_products.product_id', $this->product_id)
               ->when( $this->store_id,function ($query){
                    $query->where('stores.id', $this->store_id);
                })
               ->when($this->user_id,function ($query){
                   $query->where('users.id', $this->user_id);
               })
               ->whereNotNull('orders.check_number')

               ->groupBy('order_products.product_id')
               ->selectRaw('SUM(order_products.count) as total_count,SUM(order_products.all_price) as total_price')
               ->first();


           $this->inventories = Inventory::query()
               ->join('inventory_products', 'inventories.id', '=', 'inventory_products.inventory_id')
               ->join('stores', 'stores.id', '=', 'inventories.store_id')
               ->join('users', 'users.id', '=', 'inventories.user_id')
               ->where('inventory_products.product_id', $this->product_id)
               ->when( $this->store_id,function ($query){
                   $query->where('stores.id', $this->store_id);
               })
               ->when($this->user_id,function ($query){
                   $query->where('users.id', $this->user_id);
               })
               ->where('inventories.status',2)
               ->selectRaw('inventories.id,stores.name as store_name,
               users.name as user_name,
               inventory_products.price,
               inventory_products.count,
               inventories.created_at,
               receipt,
               remains,
               between_receipt,
               sale,
               between_sale,
               shortage,
               overage,
               between_moving_from,
               moving_from,
               between_moving_to,
               moving_to',
               )
               ->orderBy('inventories.id', 'desc')
               ->get();
           $this->movingFrom = Moving::query()
               ->join('moving_products', 'movings.id', '=', 'moving_products.moving_id')
               ->join('stores', 'stores.id', '=', 'movings.store_id')
               ->join('users', 'users.id', '=', 'movings.user_id')
               ->where('moving_products.product_id', $this->product_id)
               ->when( $this->store_id,function ($query){
                   $query->where('stores.id', $this->store_id);
               })
               ->when($this->user_id,function ($query){
                   $query->where('users.id', $this->user_id);
               })
               ->where('movings.operation',1)
               ->selectRaw('movings.id,stores.name as store_name,users.name as user_name,moving_products.price,moving_products.count,movings.created_at')
               ->orderBy('movings.id', 'desc')
               ->get();
           $this->movingTo = Moving::query()
               ->join('moving_products', 'movings.id', '=', 'moving_products.moving_id')
               ->join('stores', 'stores.id', '=', 'movings.store_id')
               ->join('users', 'users.id', '=', 'movings.user_id')
               ->where('moving_products.product_id', $this->product_id)
               ->when( $this->store_id,function ($query){
                   $query->where('stores.id', $this->store_id);
               })
               ->when($this->user_id,function ($query){
                   $query->where('users.id', $this->user_id);
               })
               ->where('movings.operation',2)
               ->selectRaw('movings.id,stores.name as store_name,users.name as user_name,moving_products.price,moving_products.count,movings.created_at')
               ->orderBy('movings.id', 'desc')
               ->get();


       }

        return view('admin.product.info_live');
    }




}
