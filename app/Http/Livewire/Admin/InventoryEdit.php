<?php

namespace App\Http\Livewire\Admin;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
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
            'createCount' => 'required|integer|min:1'
        ]);
        $product = Product::findOrFail($this->createProductId);
        $productPriceType = $product->prices()->where('price_type_id', 3)->first();
        $productPrice = $productPriceType ? $productPriceType->price : 0;
        InventoryProduct::updateOrCreate([
            'inventory_id' => $this->inventory->id,
            'product_id' => $this->createProductId
        ], [
            'inventory_id' => $this->inventory->id,
            'product_id' => $this->createProductId,
            'receipt' => 0,
            'remains' => 0,
            'count' => $this->createCount,
            'price' => $productPrice
        ]);
    }
    public function delete($id)
    {
        InventoryProduct::where('id', $id)->delete();
    }
}
