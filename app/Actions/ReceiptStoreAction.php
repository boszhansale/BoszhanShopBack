<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptProduct;
use App\Models\Reject;
use Exception;
use Illuminate\Support\Facades\Auth;

class ReceiptStoreAction
{

    /**
     * @throws Exception
     */
    public function execute($data): Receipt
    {
        $receipt = Auth::user()->receipts()->create($data);
        if (isset($data['products']))
        {
            foreach ($data['products'] as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;
                if (!isset($item['price'])){
                    $receiptProduct = ReceiptProduct::query()->join('receipts','receipts.id','receipt_products.receipt_id')
                        ->where('receipts.user_id',Auth::id())
                        ->where('receipt_products.product_id',$item['product_id'])
                        ->select('receipt_products.*')
                        ->latest()
                        ->first();
                    if ($receiptProduct){
                        $item['price'] = $receiptProduct->price;
                    }else{
                        $priceType = $product->prices()->where('price_type_id',5)->first();
                        if (!$priceType) throw new Exception("price not found");
                        $item['price'] = $priceType->price;
                    }
                }else{
                   if ($data['nds'] == 1){
                       $item['price'] = $item['price'] -  (($item['price'] / 112) * 12);
                   }
                }
                $receiptProduct = ReceiptProduct::query()->join('receipts','receipts.id','receipt_products.receipt_id')
                    ->where('receipts.user_id',Auth::id())
                    ->where('receipt_products.product_id',$item['product_id'])
                    ->select('receipt_products.*')
                    ->latest()
                    ->first();
                if ($receiptProduct){
                    $item['old_price'] = $receiptProduct->price;
                }
                $item['all_price'] = $item['count'] * $item['price'];
                $receipt->products()->updateOrCreate(['product_id' => $product->id,'receipt_id' => $receipt->id ],$item);
            }
            $receipt->update(['product_history' => $receipt->products()->select('product_id','count','price','all_price','comment')->get()->toArray(), 'total_price' => $receipt->products()->sum('all_price')]);
        }else{
            throw new Exception("products not found");
        }

        return $receipt;
    }

}
