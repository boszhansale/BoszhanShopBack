<?php

namespace App\Http\Livewire\Admin;

use App\Models\moving;
use App\Models\MovingProduct;
use App\Models\Product;
use Livewire\Component;

class MovingEdit extends Component
{
    public $moving;
    public $products;

    public $createProductId;
    public $createCount = 1;

    public function render()
    {
        return view('admin.moving.edit_live');
    }

    public function mount($movingId)
    {
        $this->moving = moving::findOrFail($movingId);
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

        MovingProduct::updateOrCreate([
            'moving_id' => $this->moving->id,
            'product_id' => $this->createProductId
        ], [
            'moving_id' => $this->moving->id,
            'product_id' => $this->createProductId,
            'count' => $this->createCount,
            'price' => $productPrice,
            'all_price' => $productPrice * $this->createCount,
            'comment' => 'добавлено через админ панель'
        ]);
    }
    public function delete($id)
    {
        MovingProduct::where('id', $id)->delete();
    }
}
