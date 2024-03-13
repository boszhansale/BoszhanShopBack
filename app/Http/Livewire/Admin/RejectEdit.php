<?php

namespace App\Http\Livewire\Admin;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Reject;
use App\Models\RejectProduct;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class RejectEdit extends Component
{
    public $reject;
    public $products;

    public $createProductId;
    public $createCount = 1;

    public function render()
    {
        return view('admin.reject.edit_live');
    }

    public function mount($rejectId)
    {
        $this->reject = Reject::findOrFail($rejectId);
        $this->products = Product::orderBy('name')->get();
    }

    public function createProduct()
    {
        $this->validate([
            'createProductId' => 'required|exists:products,id',
            'createCount' => 'required|integer|min:1'
        ]);
        $product = Product::findOrFail($this->createProductId);
        $productPriceType = $product->prices()->where('price_type_id', 3)->first();
        $productPrice = $productPriceType ? $productPriceType->price : 0;

        RejectProduct::updateOrCreate([
            'reject_id' => $this->reject->id,
            'product_id' => $this->createProductId
        ], [
            'reject_id' => $this->reject->id,
            'product_id' => $this->createProductId,
            'count' => $this->createCount,
            'price' => $productPrice,
            'all_price' => $productPrice * $this->createCount,
            'comment' => 'добавлено через админ панель'
        ]);
    }
    public function delete($id)
    {
        RejectProduct::where('id', $id)->delete();
    }
}
