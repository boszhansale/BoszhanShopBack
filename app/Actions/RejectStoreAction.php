<?php

namespace App\Actions;

use App\Models\Moving;
use App\Models\MovingProduct;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\Reject;
use Exception;
use Illuminate\Support\Facades\Auth;

class RejectStoreAction
{

    /**
     * @throws Exception
     */
    public function execute($data): Reject
    {
        $reject = Auth::user()->rejects()->create($data) ;

        if (isset($data['products'])) {

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
                    $movingProduct = MovingProduct::query()->join('movings','movings.id','moving_products.moving_id')
                        ->where('movings.user_id',Auth::id())
                        ->where('moving_products.product_id',$item['product_id'])
                        ->select('moving_products.*')
                        ->latest()
                        ->first();
                    if ($receiptProduct){
                        $item['price'] = $receiptProduct->price;
                    }elseif ($movingProduct){
                        $item['price'] = $movingProduct->price;
                    }{
                        $priceType = $product->prices()->where('price_type_id',3)->first();
                        if (!$priceType) throw new Exception("price not found: $product->id");
                        $item['price'] = $priceType->price;
                    }
                }

//                $receiptProduct = ReceiptProduct::query()
//                    ->join('receipts', 'receipts.id', 'receipt_products.receipt_id')
//                    ->where('receipts.user_id', Auth::id())
//                    ->where('product_id', $product->id)
//                    ->latest()
//                    ->select('receipt_products.*')
//                    ->first();
//
//                if (!$receiptProduct) {
//                    $reject->forceDelete();
//                    throw  new Exception("продукт $product->name не найден");
//                }

//                $item['price'] = $receiptProduct->price;
                $item['all_price'] = $item['count'] * $item['price'];

                $reject->products()->updateOrCreate([ 'product_id' => $product->id,'reject_id' => $reject->id], $item);
            }
            $reject->update([
                'product_history' => $reject->products()->select('product_id', 'count', 'price', 'all_price', 'comment')->get()->toArray(),
                'total_price' => $reject->products()->sum('all_price')
            ]);

//            $rejectAction = new RejectStoreAction();
//
//            $rejectAction->execute([
//                'source' => 2,
//                'storage_id' => Auth::user()->storage_id,
//                'store_id' => Auth::user()->store_id,
//                'organization_id' => Auth::user()->organization_id,
//                'reject_id' => $reject->id,
//                'products' => $data['products'],
//            ]);
        }

        return $reject;
    }

}
