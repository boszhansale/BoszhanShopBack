<?php

namespace App\Http\Livewire\Admin;

use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptEdit extends Component
{
    public $receipt;
    public $products;

    public $createProductId;
    public $createCount = 1;

    public function render()
    {
        return view('admin.receipt.edit_live');
    }

    public function mount($receiptId)
    {
        $this->receipt = Receipt::findOrFail($receiptId);
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
        ReceiptProduct::updateOrCreate([
            'receipt_id' => $this->receipt->id,
            'product_id' => $this->createProductId
        ], [
            'receipt_id' => $this->receipt->id,
            'product_id' => $this->createProductId,
            'count' => $this->createCount,
            'price' => $productPrice,
            'all_price' => $productPrice * $this->createCount,
            'comment' => 'добавлено из админ панель'
        ]);
    }
    public function delete($id)
    {
        ReceiptProduct::where('id', $id)->delete();
    }
}
