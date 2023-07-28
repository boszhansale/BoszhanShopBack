<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\Refund;
use App\Models\Reject;
use Exception;
use Illuminate\Support\Facades\Auth;

class RefundStoreAction
{

    /**
     * @throws Exception
     */
    public function execute($data,Order $order) :Refund
    {

        if (!isset($data['order_id'])) throw  new Exception("order_id not found");
        $refund = Auth::user()->refunds()->create($data);
        if (isset($data['products']))
        {
            foreach ($data['products'] as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;
                $orderProduct = $order->products()->where('product_id',$item['product_id'])->latest()->first();
                if (!$orderProduct){
                    $refund->forceDelete();

                    throw new Exception("продукт $product->name не найден");
                };
                if ($item['count'] > $orderProduct->count ){
                    $refund->forceDelete();

                    throw new Exception("неверный количество: $product->name");
                }
                if (!$orderProduct->price){
                    dd($orderProduct);
                }
                $item['price'] = $orderProduct->price;

                $item['all_price'] = $item['count'] * $item['price'];

                $refund->products()->updateOrCreate([
                    'product_id' => $product->id,
                    'refund_id' => $refund->id
                ],$item);
            }
            $refund->update([
                'product_history' => $refund->products()->select('product_id','count','price','all_price','comment')->get()->toArray(),
                'total_price' => $refund->products()->sum('all_price')
            ]);

            $receiptAction = new ReceiptStoreAction();
            $receiptAction->execute([
                'source' => 4,
                'operation' => 1,
                'refund_id' => $refund->id,
                'products' => $data['products'],
                'storage_id' => Auth::user()->storage_id,
                'store_id' => Auth::user()->store_id,
                'organization_id' => Auth::user()->organization_id,
            ]);
        }

        return $refund;
    }

}
