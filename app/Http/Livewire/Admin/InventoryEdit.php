<?php

namespace App\Http\Livewire\Admin;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\MovingProduct;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\RejectProduct;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class InventoryEdit extends Component
{
    public $inventory;
    public $products;

    public $createProductId;
    public $createCount = 1;

    public function render()
    {
        return view('admin.inventory.edit_live');
    }

    public function mount($inventoryId)
    {
        $this->inventory = Inventory::findOrFail($inventoryId);
        $this->products = Product::orderBy('name')->get();
    }

    public function createProduct()
    {
        $this->validate([
            'createProductId' => 'required|exists:products,id',
            'createCount' => 'required'
        ]);
        $product = Product::findOrFail($this->createProductId);
        $productPriceType = $product->prices()->where('price_type_id', 3)->first();
        $productPrice = $productPriceType ? $productPriceType->price : 0;

        $receipt = ReceiptProduct::query()
            ->join('receipts', 'receipt_products.receipt_id', '=', 'receipts.id')
            ->where('receipt_products.product_id', $this->createProductId)
            ->where('receipts.store_id', $this->inventory->store_id)
            ->groupBy('receipt_products.product_id')
            ->sum('receipt_products.count');

        $movingFrom = MovingProduct::query()
            ->join('movings', 'moving_products.moving_id', '=', 'movings.id')
            ->where('moving_products.product_id', $this->createProductId)
            ->where('movings.store_id', $this->inventory->store_id)
            ->where('movings.operation',1)
            ->groupBy('moving_products.product_id')
            ->sum('moving_products.count');
        $movingTo = MovingProduct::query()
            ->join('movings', 'moving_products.moving_id', '=', 'movings.id')
            ->where('moving_products.product_id', $this->createProductId)
            ->where('movings.store_id', $this->inventory->store_id)
            ->where('movings.operation',2)

            ->groupBy('moving_products.product_id')
            ->sum('moving_products.count');
        $sale = OrderProduct::query()
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('order_products.product_id', $this->createProductId)
            ->where('orders.store_id', $this->inventory->store_id)
            ->groupBy('order_products.product_id')
            ->sum('order_products.count');

        $reject = RejectProduct::query()
            ->join('rejects', 'reject_products.reject_id', '=', 'rejects.id')
            ->where('reject_products.product_id', $this->createProductId)
            ->where('rejects.store_id', $this->inventory->store_id)
            ->groupBy('reject_products.product_id')
            ->sum('reject_products.count');


        InventoryProduct::updateOrCreate([
            'inventory_id' => $this->inventory->id,
            'product_id' => $this->createProductId
        ], [
            'inventory_id' => $this->inventory->id,
            'product_id' => $this->createProductId,

            'receipt' => $receipt,
            'remains' => $receipt + $movingFrom - $sale - $movingTo - $reject,
            'sale' => $sale,
            'moving_from' => $movingFrom,
            'moving_to' => $movingTo,

            'count' => $this->createCount,
            'price' => $productPrice
        ]);
    }
    public function delete($id)
    {
        InventoryProduct::where('id', $id)->delete();
    }
}
